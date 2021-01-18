<?php namespace App\Modules\Api\Terminal;

use App\Base\Contracts\ShippingServiceLocator;
use App\Base\Models\RabbitMqConsumerLog;
use App\Base\Models\Shipment;
use App\Base\Models\ShippingLabel;
use App\Base\Services\RabbitMQService;
use App\Base\Models\ShipmentRemoteApiLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RegisterShipment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipment:register {shipping company code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RabbitMQ worker process to register shipments at provided shipping company ';

    /**
     * The shipping service locator instance
     *
     * @var ShippingServiceLocator
     */
    protected $shippingServiceLocator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ShippingServiceLocator $shippingServiceLocator)
    {
        parent::__construct();
        $this->shippingServiceLocator = $shippingServiceLocator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $shippingCompanyCode = $this->argument('shipping company code');
        $rbms = new RabbitMQService();

        $rbms->registerShipmentRegistrationWorker($shippingCompanyCode,
            function ($message) use ($shippingCompanyCode, $rbms) {

                $shipment_id = $message->body;

                $rabbitMqLog = new RabbitMqConsumerLog();
                $rabbitMqLog->shipment_id = $shipment_id;
                $rabbitMqLog->consumer_class = static::class;

                $shipment = Shipment::find($shipment_id);
                if (is_null($shipment)) {
                    $rabbitMqLog->publish_data = $shipment_id;
                    $rabbitMqLog->error_messages = '["Shipment with id \'' . $shipment_id . '\' not found"]';
                    $rabbitMqLog->error = RabbitMqConsumerLog::NO_SHIPMENT_ERROR;
                    $rabbitMqLog->save();

                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                    return;
                }

                $rabbitMqLog->publish_data = json_encode($shipment);

                $remoteService = $this->shippingServiceLocator->getShippingService(
                    $shippingCompanyCode,
                    $shipment->settings
                );

                if ($remoteService->hasErrors()) {
                    $rabbitMqLog->error_messages = json_encode([get_class($remoteService) => $remoteService->getErrors()]);
                    $rabbitMqLog->error = RabbitMqConsumerLog::REMOTE_CONNECTION_ERROR;
                    $rabbitMqLog->save();

                    $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_ERR_CONNECT;
                    $shipment->save();

                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                    return;
                }

                $shipmentResponse = $remoteService->createShipment($shipment);

                $logPivot = new ShipmentRemoteApiLog();
                $logPivot->shipment_id = $shipment_id;
                $logPivot->remote_api_log_id = $shipmentResponse->getLog()->id;
                $logPivot->type = ShipmentRemoteApiLog::LOG_TYPE_CREATE_SHIPMENT;
                $logPivot->save();

                if ($shipmentResponse->hasErrors()) {
                    $rabbitMqLog->error_messages = json_encode([get_class($remoteService) => $remoteService->getErrors()]);
                    $rabbitMqLog->error = RabbitMqConsumerLog::REMOTE_REGISTRATION_ERROR;
                    $rabbitMqLog->save();

                    $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_ERR_REGISTER;
                    $shipment->save();

                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                    return;
                }

                $shipment->remote_tracking_code = $shipmentResponse->getTrackingCode();
                $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_LABEL_REGISTERED;
                $shipment->save();

                $labelId = $shipmentResponse->getLabelId();
                $rabbitMqLog->consume_data = json_encode([
                    'trackingCodes' => [
                        'local' => $shipment->local_tracking_code,
                        'remote' => $shipment->remote_tracking_code,
                    ],
                    'labelId' => $labelId,
                ]);

                $labelResponse = $remoteService->getLabel($labelId);
                $logPivot = new ShipmentRemoteApiLog();
                $logPivot->shipment_id = $shipment_id;
                $logPivot->remote_api_log_id = $labelResponse->getLog()->id;
                $logPivot->type = ShipmentRemoteApiLog::LOG_TYPE_GET_LABEL;
                $logPivot->save();

                if ($labelResponse->hasErrors()) {
                    $rabbitMqLog->error_messages = '["Failed to download shipping label from remote service"]';
                    $rabbitMqLog->error = RabbitMqConsumerLog::LABEL_DOWNLOAD_ERROR;
                    $rabbitMqLog->save();

                    $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_ERR_LABEL;
                    $shipment->save();

                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                    return;
                }

                $shippingLabel = ShippingLabel::firstOrNew(['uuid' => $labelId]);
                if ($shippingLabel->exists) {
                    $rabbitMqLog->error_messages = '["Shipping label uuid already exists"]';
                    $rabbitMqLog->error = RabbitMqConsumerLog::LABEL_EXISTS_ERROR;
                    $rabbitMqLog->save();

                    $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_ERR_LABEL;
                    $shipment->save();

                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                    return;
                }

                if (Storage::put($shippingLabel->storage_path, base64_decode($labelResponse->getPdf()))) {
                    $shippingLabel->shipment_id = $shipment->id;
                    $shippingLabel->save();

                    $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_LABEL_DOWNLOADED;
                    $shipment->save();

                    $rabbitMqLog->error = RabbitMqConsumerLog::NO_ERROR;
                    $rabbitMqLog->save();
                } else {
                    $rabbitMqLog->error_messages = '["Failed to save shipping label to local storage"]';
                    $rabbitMqLog->error = RabbitMqConsumerLog::LABEL_SAVE_ERROR;
                    $rabbitMqLog->save();
                }

                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            }
        );
    }

}
