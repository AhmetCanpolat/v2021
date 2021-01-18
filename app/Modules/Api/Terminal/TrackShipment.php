<?php namespace App\Modules\Api\Terminal;

use App\Base\Contracts\ShippingServiceLocator;
use App\Base\Models\RabbitMqConsumerLog;
use App\Base\Models\Shipment;
use App\Base\Models\ShipmentRemoteApiLog;
use App\Base\Models\WebhookSetting;
use App\Base\Services\RabbitMQService;
use App\Modules\Api\Webhooks\TrackingWebhook;
use Illuminate\Console\Command;
use Storage;

class TrackShipment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipment:track {shipping company code}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RabbitMQ worker process to track shipments registered at provided shipping company';

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


//        $check = Storage::exists('tracklog/filename.txt');
//        if($check == true)
//     c   {
//            Storage::disk('local')->put('tracklog/filename.txt', 'File content goes here..');
//        }else{
//            Storage::append ( 'tracklog/filename.txt' , 'Bu metni ekle');
//        }

        $rbms->registerShipmentTrackingWorker($shippingCompanyCode,
            function ($message) use ($shippingCompanyCode, $rbms) {


                $shipment_id = $message->body;

                $rabbitMqLog = new RabbitMqConsumerLog();
                $rabbitMqLog->shipment_id = $shipment_id;
                $rabbitMqLog->consumer_class = static::class;

                $shipment = Shipment::find($message->body);


                if (is_null($shipment)) {
                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

                    $rabbitMqLog->error_messages = json_encode([
                        "Shipment with id $shipment_id not found",
                    ]);
                    $rabbitMqLog->error = RabbitMqConsumerLog::NO_SHIPMENT_ERROR;
                    $rabbitMqLog->save();
                    return;
                }

                if($shipment->shipping_status_code < Shipment::SHIPPING_STATUS_LABEL_PRINTED) {
                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

                    $rabbitMqLog->error_messages = json_encode([
                        "Shipment ($shipment->local_tracking_code) tracking paused or completed",
                    ]);
                    $rabbitMqLog->error = RabbitMqConsumerLog::UNREADY_FOR_TRACKING_ERROR;
                    $rabbitMqLog->save();

                    return;
                }

                if(
                    $shipment->tracking_status_code <= Shipment::TRACKING_STATUS_PAUSED &&
                    $shipment->tracking_status_code >= Shipment::TRACKING_STATUS_COMPLETED
                ) {
                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

                    $rabbitMqLog->error_messages = json_encode([
                        "Shipment ($shipment->local_tracking_code) tracking paused or completed",
                    ]);
                    $rabbitMqLog->error = RabbitMqConsumerLog::TRACKING_STOPPED_ERROR;
                    $rabbitMqLog->save();

                    return;
                }

                $shipment->tracking_status_code = Shipment::TRACKING_STATUS_WORKING;
                $shipment->save();

                $remoteService = $this->shippingServiceLocator->getShippingService(
                    $shipment->company->code,
                    $shipment->settings
                );

                $trackingResponse = $remoteService->trackShipment($shipment);

                $logPivot = new ShipmentRemoteApiLog();
                $logPivot->shipment_id = $shipment_id;
                $logPivot->remote_api_log_id = $trackingResponse->getLog()->id;
                $logPivot->type = ShipmentRemoteApiLog::LOG_TYPE_TRACK_SHIPMENT;
                $logPivot->save();

                if ($trackingResponse->hasErrors()) {
                    $rabbitMqLog->error_messages = json_encode([get_class($remoteService) => $remoteService->getErrors()]);
                    $rabbitMqLog->error = RabbitMqConsumerLog::REMOTE_CONNECTION_ERROR;
                    $rabbitMqLog->save();

                    $shipment->tracking_status_code = Shipment::TRACKING_STATUS_IN_QUEUE;
                    $shipment->save();

                    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                    $rbms->publishShipmentTrackingRequest($shipment);
                    return;
                }

                $newEvents = $trackingResponse->getEvents();
                $newStatus = $trackingResponse->getShippingStatus();

                if ($statusChanged = ($shipment->shipping_status_code != $newStatus)) {
                    $shipment->shipping_status_code = $newStatus;
                }

                if ($eventsChanged = ($shipment->events != $newEvents)) {
                    $shipment->events = $newEvents;
                }



                $webhookSettings = collect();
                if ($statusChanged || $eventsChanged) {
                    $shipment->reported = false;
                    $webhookSettings = WebhookSetting::where(
                        'client_id', $shipment->settings->client_id
                    )->orWhere('setting_id', $shipment->settings_id)->get();
                }

                if ($eventsChanged) {
                    $eventHook = $webhookSettings->where(
                        'type', WebhookSetting::WEBHOOK_TYPE_EVENT)->sortBy('client_id')->first();
                    if (isset($eventHook)) {
                        $webhookResult = new TrackingWebhook($eventHook->uri, $eventHook->secret, $shipment);
                        if (!$webhookResult->hasErrors()) {
                            $shipment->reported = true;
                        }
                    }
                }

                if ($statusChanged) {
                    $statusHook = $webhookSettings->where(
                        'type', WebhookSetting::WEBHOOK_TYPE_STATUS)->sortBy('client_id')->first();
                    if (isset($statusHook)) {
                        if (empty($eventHook) || $eventHook->uri != $statusHook->uri) {
                            $webhookResult = new TrackingWebhook($statusHook->uri, $statusHook->secret, $shipment);
                            if (!$webhookResult->hasErrors()) {
                                $shipment->reported = true;
                            }
                        }
                    }
                }

                if ($newStatus == Shipment::SHIPPING_STATUS_DELIVERED) {
                    $shipment->tracking_status_code = Shipment::TRACKING_STATUS_COMPLETED;
                    $deliveredHook = $webhookSettings->where(
                        'type', WebhookSetting::WEBHOOK_TYPE_DELIVERED)->sortBy('client_id')->first();
                    if (isset($deliveredHook)) {
                        if (
                            (empty($statusHook) || $statusHook->uri != $deliveredHook->uri) ||
                            (empty($eventHook) || $eventHook->uri != $deliveredHook->uri)
                        ) {
                            $webhookResult = new TrackingWebhook($deliveredHook->uri, $deliveredHook->secret, $shipment);
                            if (!$webhookResult->hasErrors()) {
                                $shipment->reported = true;
                            }
                        }
                    }
                } else {
                    $shipment->tracking_status_code = Shipment::TRACKING_STATUS_IN_QUEUE;
                    $rbms->publishShipmentTrackingRequest($shipment);
                }

                $shipment->save();
                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

            }
        );
    }

}
