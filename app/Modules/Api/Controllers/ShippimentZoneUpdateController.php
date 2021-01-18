<?php namespace App\Modules\Api\Controllers;

use App\Base\Models\ShippingCompany;
use App\Base\Models\ShippingCompanySetting;
use App\Base\Models\User;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Base\Locators\ShippingServiceLocator;

class ShippimentZoneUpdateController extends ApiController
{
    public function handle()
    {
        $user = User::find(Auth::id());
        $shippingService = new ShippingServiceLocator();
        $shippings = ShippingCompany::get();
        foreach($shippings as $val)
        {
            if(!empty($val["zone_update_link"]))
            {
                $shippingCompany = ShippingCompany::where('code', $val["code"])->first();
                $settings = ShippingCompanySetting::where(['client_id' => $user->client_id,'shipping_company_id' => $shippingCompany->id])->first();
                $serviceParams = $shippingService->getShippingService($val["code"],$settings);
                $request = $serviceParams->updateZone($val["id"]);
            }
        }
    }
}
