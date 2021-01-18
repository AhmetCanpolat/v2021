<?php namespace App\Base\Locators;

use App\Base\Abstracts\ShippingService;
use App\Base\Contracts\ShippingServiceLocator as ShippingServiceLocatorContract;
use App\Base\Models\ShippingCompanySetting;
use App\Modules\Api\ShippingServices\Dhl\DhlService;
use App\Modules\Api\ShippingServices\Byex\ByexService;
use App\Modules\Api\ShippingServices\Surat\SuratService;
use App\Modules\Api\ShippingServices\Aras\MngService;
use App\Modules\Api\ShippingServices\PackUpp\PackUppService;

class ShippingServiceLocator implements ShippingServiceLocatorContract
{
    public function getShippingService(string $shippingCompanyCode, ShippingCompanySetting $userSettings): ShippingService
    {
        switch ($shippingCompanyCode) {
            case 'dhl':
                return new DhlService($userSettings);
            case 'byex':
                return new ByexService($userSettings);
            case 'surat':
                return new SuratService($userSettings);
            case 'aras':
                return new MngService($userSettings);
            case 'packu':
                return new PackUppService($userSettings);
            default:
                # code...
                break;
        }
    }
}
