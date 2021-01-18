<?php namespace App\Base\Contracts;

interface TrackShipment extends RemoteRestRequest
{
    public function getEvents(): array;
    public function getShippingStatus(): int;
}
