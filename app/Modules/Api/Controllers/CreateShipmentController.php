<?php namespace App\Modules\Api\Controllers;

use App\Base\Abstracts\ShippingService;
use App\Base\Models\RabbitMqConsumerLog;
use App\Base\Models\Shipment;
use App\Base\Models\ShippingCompany;
use App\Base\Models\ShippingCompanySetting;
use App\Base\Models\ShippingZone;
use App\Base\Models\User;
use App\Base\Services\RabbitMQService;
use App\Base\Locators\ShippingServiceLocator as Lacator;
use App\Modules\Api\Validation\ShipmentInfoValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CreateShipmentController extends ApiController
{
    public function handle(Request $request)
    {

        $data = $request->json()->all();


        if ($data == []) {
            return response()->json([
                'message' => 'Your request json object is empty. This probably means your json is invalid',
                'success' => false,
            ]);
        }

        if (isset($data['shippingCompany'])) {
            $data['shippingCompany'] = strtolower($data['shippingCompany']);
        }

        $validator = new ShipmentInfoValidator($data);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }
        $data = $validator->validated();

        $user = User::find(Auth::id());
        $shippingCompany = ShippingCompany::where('code', $request->input('shippingCompany'))->first();

        /* TODO
         *
         * Bu alanda ülke il ilçe kontrolü sağlanmaktadır.
         */

        if($data['shippingCompany'] == "byex")
        {
            $zoneControl =  ShippingZone::where([
                'shipping_id' => $shippingCompany->id,
                'city' => mb_strtoupper($data['receiver']['cityName'], 'UTF-8'),
                'state' => mb_strtoupper($data['receiver']['districtName'], 'UTF-8')
            ])->count();
            if($zoneControl == 0)
            {
                return response()->json([
                    'message' => 'Zones failed',
                    'success' => false,
                    'errors' => "Zone not found !",
                ]);
            }
        }


        /*
         *
         * Bu alanda client id değerinin bağlı olduğu kargo firmasının id sini alıyor
         */
        $settings = ShippingCompanySetting::where([
            'client_id' => $user->client_id, /* saas yapı için kullanılıyor !!! */
            'shipping_company_id' => $shippingCompany->id,
        ])->first();

        $shipment = Shipment::firstOrNew(['local_tracking_code' => $data['trackingCode']]);
        $shipmentExists = $shipment->exists;

        $shipment->tracking_status_code = Shipment::TRACKING_STATUS_JUST_RECEIVED;
        $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_DATA_RECEIVED;
        $shipment->client_id = $user->client_id;
        $shipment->shipping_company_id = $shippingCompany->id;
        $shipment->settings_id = $settings->id;
        $shipment->request = $data;
        $shipment->save();

        /* TO DO
         *
         * Debug için kullanılıyor. Canlıya Çıkmayacak
         */
//
//        $serviceSelect = new Lacator();
//        $serviceParams = $serviceSelect->getShippingService($data['shippingCompany'],$settings);
//        $servicePost = $serviceParams->createShipment($shipment);
//        print_r($servicePost);
//        exit;

        try {
            $rmqs = new RabbitMQService();
            $rmqs->publishShipmentRegistrationRequest($shipment);
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Failed to enque job',
                'success' => false,
                'errors' => [$exception->getMessage()],
            ]);

            /* LOG ERROR SOMEWHERE */

        }

        $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_IN_QUEUE;
        $shipment->save();

        return response()->json([
            'message' => 'Published message to registration queue',
            'action' => $shipmentExists ? 'updated' : 'created',
            'success' => true,
        ]);

    }

}
