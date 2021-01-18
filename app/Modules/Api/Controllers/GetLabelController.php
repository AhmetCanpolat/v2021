<?php namespace App\Modules\Api\Controllers;

use App\Base\Contracts\ShippingServiceLocator;
use App\Base\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetLabelController extends ApiController
{

    /**
     * The shipping service locator instance
     *
     * @var ShippingServiceLocator
     */
    protected $shippingServiceLocator;

    public function __construct(ShippingServiceLocator $shippingServiceLocator)
    {
        $this->shippingServiceLocator = $shippingServiceLocator;
    }

    public function handle(Request $request)
    {
        $localTrackingCode = $request->input('local_tracking_code');

        if (is_null($localTrackingCode)) {

            return response()->json([
                'message' => 'Bad request. Must provide parameter \'local_tracking_code\'',
                'success' => false,
            ]);

        }

        $user = User::find(Auth::id());
        $shipment = $user->client->shipments->where('local_tracking_code', $localTrackingCode)->first();
        if (isset($shipment)) {

            if ($shipment->labels->isEmpty()) {
                return response()->json([
                    'message' => 'Shipment with tracking code \'' . $localTrackingCode . '\' does not have any labels',
                    'success' => false,
                ]);
            }

            return response()->json([
                'message' => 'Label(s) found',
                'success' => true,
                'labels' => $shipment->labels->pluck('url', 'uuid'),
            ]);

        } else {

            return response()->json([
                'message' => 'Shipment with tracking code \'' . $localTrackingCode . '\' not found',
                'success' => false,
            ]);

        }

    }

}
