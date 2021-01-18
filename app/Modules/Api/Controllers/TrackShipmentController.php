<?php namespace App\Modules\Api\Controllers;

use App\Base\Models\Shipment;
use App\Base\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackShipmentController extends ApiController
{
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

            return response()->json([
                'status' => Shipment::SHIPPING_STATUS_STRINGS[$shipment->shipping_status_code],
                'code' => $shipment->shipping_status_code,
                'events' => $shipment->events,
            ]);

        } else {

            return response()->json([
                'message' => 'Shipment with tracking code \'' . $localTrackingCode . '\' not found',
                'success' => false,
            ]);

        }
    }
}
