<?php namespace App\Base\Contracts;

use App\Base\Abstracts\ShippingService;
use App\Base\Models\ShippingCompanySetting;

interface ShippingServiceLocator 
{
    public function getShippingService(string $shippingCompanyCode, ShippingCompanySetting $userSettings): ShippingService;
}