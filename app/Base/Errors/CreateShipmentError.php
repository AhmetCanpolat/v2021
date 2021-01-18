<?php namespace App\Base\Errors;

use App\Base\Contracts\CreateShipment;
use App\Base\Traits\HasErrors;

class CreateShipmentError implements CreateShipment
{
    use HasErrors;

    public function __construct(array $errors)
    {
        $this->addErrors($errors);
    }

    public function getShipmentId(): string
    {
        return '';
    }

    public function getTrackingCode(): string
    {
        return '';
    }

    public function getPieces(): array
    {
        return [];
    }

    public function getLabelId(): string
    {
        return '';
    }

}
