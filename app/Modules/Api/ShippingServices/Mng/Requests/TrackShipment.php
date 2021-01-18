<?php namespace App\Modules\Api\ShippingServices\Mng\Requests;

use App\Base\Abstracts\ShippingService;
use App\Base\Contracts\TrackShipment as TrackShipmentContract;
use App\Base\Models\Shipment;
use DateTimeImmutable;

final class TrackShipment extends MngRequest implements TrackShipmentContract
{

    protected $base_uri;
    protected $path = 'GetOrderWithIntegrationCode';
    protected $method = 'soap';
    protected $options = [
        'auth' => [
            'UserName' => 'application/json',
            'Password' => 'application/json',
        ],

        'headers' => [
            'Authentication' => 'Authentication',
            'url' => 'http://tempuri.org/',
        ],
    ];

    private $trackingKey;
    private $shippingStatus;

    private const STATUS_TRANSLATIONS = [
        'DATA_RECEIVED' => Shipment::SHIPPING_STATUS_LABEL_PRINTED,
        'UNDERWAY' => Shipment::SHIPPING_STATUS_UNDERWAY,
        'IN_DELIVERY' => Shipment::SHIPPING_STATUS_IN_DELIVERY,
        'DELIVERED' => Shipment::SHIPPING_STATUS_DELIVERED,
        'PROBLEM' => Shipment::SHIPPING_STATUS_PROBLEM,
    ];

    public function __construct(ShippingService $remoteService, string $trackingKey)
    {
        $userConfig = $remoteService->getUserConfig();
        $this->trackingKey = $trackingKey;
        $trakingconfig  = array("UserName" => $this->userConfig->connection_settings['UserName'],"Password" => $this->userConfig->connection_settings['UserName'],"integrationCode" => $trackingKey);
        $this->options['auth'] = array("UserName" => $this->userConfig->connection_settings['UserName'],"Password" => $this->userConfig->connection_settings['UserName'] );
        $this->options['body'] = json_encode($trakingconfig,JSON_NUMERIC_CHECK);
        parent::__construct($remoteService);
    }

    public function prepareRequest(): void
    {
        if (config('shippi.active_env') == 'testing') {
            $this->base_uri = 'http://localhost:7751';
            $this->path = 'returnTrackingInfo';
        }
    }

    protected function onSuccess(): void
    {
        $content = json_decode($this->getContent())[0];
        $events = [];

        foreach (($content->events ?? []) as $event) {
            $dti = new DateTimeImmutable($event->localTimestamp ?? '');
            if ($event->category == 'LEG') {
                continue;
            }
            $events[$dti->getTimestamp()] = [
                'localTime' => $event->localTimestamp ?? '',
                'status' => $event->category ?? '',
                'detail' => $event->status ?? '',
                'location' => $event->facility ?? '',
                'remarks' => $event->remarks ?? '',
            ];
        }

        ksort($events);

        $status = self::STATUS_TRANSLATIONS[end($events)['status']] ?? Shipment::SHIPPING_STATUS_UNRECOGNIZED;

        $this->setEvents($events)->setShippingStatus($status);
    }

    /**
     * @return string
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @param string $event
     * @return TrackShipment
     */
    private function setEvents(array $events): TrackShipment
    {
        $this->events = $events;
        return $this;
    }

    /**
     * @return string
     */
    public function getShippingStatus(): int
    {
        return $this->shippingStatus;
    }

    /**
     * @param string $status
     * @return TrackShipment
     */
    private function setShippingStatus(int $status): TrackShipment
    {
        $this->shippingStatus = $status;
        return $this;
    }

}
