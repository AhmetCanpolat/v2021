<?php namespace App\Modules\Api\Controllers;

use App\Base\Models\Shipment;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetShipmentInfoController extends ApiController
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

        $user = Auth::user();
        $shipment = $user->client->shipments->where('local_tracking_code', $localTrackingCode)->first();
        if (isset($shipment)) {

            if (empty($shipment->events)) {
                $processDate = $shipment->updated_at;
            } else {
                $processDate = new DateTimeImmutable();
                $processDate = $processDate->setTimestamp(max(array_keys($shipment->events)));
            }

            $turkey = new DateTimeZone('Europe/Istanbul');

            $processDate = $processDate->setTimezone($turkey)->format(DATE_ATOM);
            $updateDate = (new DateTimeImmutable($shipment->updated_at))->setTimezone($turkey)->format(DATE_ATOM);
            $createDate = (new DateTimeImmutable($shipment->created_at))->setTimezone($turkey)->format(DATE_ATOM);

            return response()->json([
                'status' => Shipment::SHIPPING_STATUS_STRINGS[$shipment->shipping_status_code],
                'status_code' => $shipment->shipping_status_code,
                'tracking_code' => $shipment->remote_tracking_code,
                'process_date' => $processDate,
                'update_date' => $updateDate,
                'create_date' => $createDate,
                'labels' => $shipment->labels->pluck('url', 'uuid'),
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
