<?php namespace App\Base\Abstracts;

use App\Base\Contracts\CreateShipment;
use App\Base\Contracts\GetLabel;
use App\Base\Contracts\TrackShipment;
use App\Base\Models\Shipment;
use App\Base\Models\ShippingCompanySetting;
use App\Base\Traits\HasErrors;

abstract class ShippingService
{

    use HasErrors;

    /**
     * A user's shipping company configuration
     *
     * @var \App\Base\Models\ShippingCompanySetting
     */
    protected $userConfig;

    public function __construct(ShippingCompanySetting $userConfig)
    {
        $this->userConfig = $userConfig;
    }

    public function getUserConfig()
    {
        return $this->userConfig;
    }

    abstract public function createShipment(Shipment $shipment): CreateShipment;

    abstract public function getLabel(string $trackingCode): GetLabel;

    abstract public function trackShipment(Shipment $shipment): TrackShipment;

}
