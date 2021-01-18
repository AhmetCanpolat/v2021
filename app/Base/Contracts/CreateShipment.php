<?php namespace App\Base\Contracts;

interface CreateShipment extends RemoteRestRequest
{

    public function getShipmentId(): string;

    public function getTrackingCode(): string;

    public function getPieces(): array;

    public function getLabelId(): string;

}
