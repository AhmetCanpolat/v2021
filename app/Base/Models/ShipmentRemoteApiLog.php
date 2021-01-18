<?php namespace App\Base\Models;

use App\Base\Traits\LocalizedTimestamps;
use Illuminate\Database\Eloquent\Model;

class ShipmentRemoteApiLog extends Model
{
    use LocalizedTimestamps;

    public $timestamps = false;

    protected $primaryKey = 'remote_api_log_id';

    public const LOG_TYPE_CREATE_SHIPMENT = 10;
    public const LOG_TYPE_GET_LABEL = 20;
    public const LOG_TYPE_TRACK_SHIPMENT = 30;
    public const LOG_TYPE_WEBHOOK_CALL = 40;

    public const LOG_TYPE_NAMES = [
        self::LOG_TYPE_CREATE_SHIPMENT => 'CREATE SHIPMENT',
        self::LOG_TYPE_GET_LABEL => 'GET LABEL',
        self::LOG_TYPE_TRACK_SHIPMENT => 'TRACK SHIPMENT',
        self::LOG_TYPE_WEBHOOK_CALL => 'WEBHOOK CALL',
    ];

    public function log()
    {
        return $this->belongsTo(RemoteApiLog::class, 'remote_api_log_id', 'id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
