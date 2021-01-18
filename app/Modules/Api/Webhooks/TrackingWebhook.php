<?php namespace App\Modules\Api\Webhooks;

use App\Base\Abstracts\RemoteRestRequest;
use App\Base\Models\Shipment;
use App\Base\Models\ShipmentRemoteApiLog;
use DateTimeImmutable;
use DateTimeZone;

final class TrackingWebhook extends RemoteRestRequest
{
    protected $base_uri = '';
    protected $path;
    protected $method = 'POST';
    protected $options = [
        'defaults' => [
            'verify' => false
        ]
    ];

    /**
     * The shipment to execute the tracking webhook on.
     *
     * @var \App\Base\Models\Shipment
     */
    protected $shipment;

    public function __construct(
        string $uri,
        string $token,
        Shipment $shipment
    ) {

        $this->shipment = $shipment;
         if (empty($shipment->events)) {
            $dto = $shipment->updated_at;
        } else {
            $dto = new DateTimeImmutable();
            $dto = $dto->setTimestamp(max(array_keys($shipment->events)));
        }

        $dto = $dto->setTimezone(new DateTimeZone('Europe/Istanbul'));
        $turkish_date = $dto->format('Y-m-d H:i:s');

        $this->path = $uri;
        $this->options['form_params'] = [
            'token' => $token,
            'env' => 'production',
            'data[shipping_barcode]'   => $shipment->local_tracking_code,
            'data[tracking_code]'      => $shipment->remote_tracking_code,
            'data[tracking_detail]'    => json_encode($shipment->events),
            'data[date_process]'       => $turkish_date,
            'data[status]'             => $shipment->shipping_status_code,
        ];
        parent::__construct();
    }

    protected function onSuccess(): void
    {
        $response = json_decode($this->getContent());
        $success = json_decode($response->result)->result;

        if (!$success) {
            $this->addError('Webhook reported failure');
        }

        if (isset($response->error)) {
            $this->addError($response->error);
        }
    }

    protected function onComplete(): void
    {
        $logPivot = new ShipmentRemoteApiLog();
        $logPivot->shipment_id = $this->shipment->id;
        $logPivot->remote_api_log_id = $this->getLog()->id;
        $logPivot->type = ShipmentRemoteApiLog::LOG_TYPE_WEBHOOK_CALL;
        $logPivot->save();
    }

}
