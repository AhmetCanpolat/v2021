<?php namespace App\Modules\Api\Controllers;

use App\Base\Models\Shipment;
use App\Base\Models\User;
use App\Base\Services\RabbitMQService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportLabelPrintController extends ApiController
{
    public function handle(Request $request)
    {
        $localTrackingCode = $request->input('local_tracking_code');

        if (is_null($localTrackingCode)) {

            return response()->json([
                'message' => 'Bad request. Must provide parameter \'local_tracking_code\'.',
                'success' => false,
            ], 400);

        }
        $user = User::find(Auth::id());
        $shipment = $user->client->shipments->where('local_tracking_code', $localTrackingCode)->first();
        if (isset($shipment)) {

            if (is_null($shipment->remote_tracking_code)) {
                return response()->json([
                    'message' => "Shipment $localTrackingCode was not registered.",
                    'success' => false,
                ], 400);
            }

            if ($shipment->labels->isEmpty()) {
                return response()->json([
                    'message' => "Shipment $localTrackingCode does not have any labels.",
                    'success' => false,
                ], 400);
            }

            $shipment->shipping_status_code = Shipment::SHIPPING_STATUS_LABEL_PRINTED;
            $shipment->reported = false;
            $shipment->save();

            if (
                $shipment->tracking_status_code >= Shipment::TRACKING_STATUS_IN_QUEUE &&
                $shipment->tracking_status_code <= Shipment::TRACKING_STATUS_PAUSED
            ) {
                return response()->json([
                    'message' => 'Shipment already tracked (label has been printed before)',
                    'success' => false,
                ], 400);
            } else {

                $rbms = new RabbitMQService;
                $environment = config('shippi.active_env');
                $delay = config("shippi.env.$environment.initial_tracking_delay");
                $published = $rbms->publishShipmentTrackingRequest($shipment, $delay);

                if ($published) {
                    $shipment->tracking_status_code = Shipment::TRACKING_STATUS_IN_QUEUE;
                    $shipment->save();
                    return response()->json([
                        'message' => 'Published shipment tracking request to queue.',
                        'success' => true,
                    ]);
                } else {
                    $shipment->tracking_status_code = Shipment::TRACKING_STATUS_QUEUE_ERROR;
                    $shipment->save();
                    return response()->json([
                        'message' => 'Failed to publish tracking request to queue.',
                        'success' => false,
                    ], 503);
                }
            }

        } else {

            return response()->json([
                'message' => 'Shipment with tracking code \'' . $localTrackingCode . '\' not found.',
                'success' => false,
            ], 400);

        }
    }
}
