<?php

namespace App\Base\Models;

use App\Base\Traits\LocalizedTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \Illuminate\Database\Eloquent\Collection $labels
 */
class Shipment extends Model
{

    use SoftDeletes, LocalizedTimestamps;

    protected $casts = [
        'request' => 'json',
        'events' => 'json',
    ];

    protected $fillable = ['local_tracking_code'];

    public const TRACKING_STATUS_JUST_RECEIVED = 0;
    public const TRACKING_STATUS_IN_QUEUE = 10;
    public const TRACKING_STATUS_WORKING = 20;
    public const TRACKING_STATUS_COMPLETED = 30;
    public const TRACKING_STATUS_PAUSED = 40;
    public const TRACKING_STATUS_QUEUE_ERROR = 92;

    public const TRACKING_STATUS_STRINGS = [
        self::TRACKING_STATUS_JUST_RECEIVED => 'JUST RECEIVED',
        self::TRACKING_STATUS_IN_QUEUE => 'IN QUEUE',
        self::TRACKING_STATUS_WORKING => 'WORKING',
        self::TRACKING_STATUS_COMPLETED => 'COMPLETED',
        self::TRACKING_STATUS_PAUSED => 'PAUSED',
        self::TRACKING_STATUS_QUEUE_ERROR => 'QUEUE ERROR',
    ];

    public function getTrackingStatusAttribute()
    {
        return self::TRACKING_STATUS_STRINGS[$this->tracking_status_code];
    }

    const SHIPPING_STATUS_DATA_RECEIVED = 0;
    const SHIPPING_STATUS_IN_QUEUE = 10;
    const SHIPPING_STATUS_LABEL_REGISTERED = 15;
    const SHIPPING_STATUS_LABEL_DOWNLOADED = 20;
    const SHIPPING_STATUS_LABEL_PRINTED = 25;
    const SHIPPING_STATUS_UNDERWAY = 30;
    const SHIPPING_STATUS_IN_DELIVERY = 40;
    const SHIPPING_STATUS_DELIVERED = 50;

    const SHIPPING_STATUS_PROBLEM = 90;
    const SHIPPING_STATUS_ERR_CONNECT = 92;
    const SHIPPING_STATUS_ERR_REGISTER = 94;
    const SHIPPING_STATUS_ERR_LABEL = 96;
    const SHIPPING_STATUS_UNRECOGNIZED = 99;

    const SHIPPING_STATUS_STRINGS = [

        self::SHIPPING_STATUS_DATA_RECEIVED => 'DATA_RECEIVED',
        self::SHIPPING_STATUS_IN_QUEUE => 'IN QUEUE',
        self::SHIPPING_STATUS_LABEL_REGISTERED => 'LABEL REGISTERED',
        self::SHIPPING_STATUS_LABEL_DOWNLOADED => 'LABEL DOWNLOADED',
        self::SHIPPING_STATUS_LABEL_PRINTED => 'LABEL PRINTED',
        self::SHIPPING_STATUS_UNDERWAY => 'UNDERWAY',
        self::SHIPPING_STATUS_IN_DELIVERY => 'IN_DELIVERY',
        self::SHIPPING_STATUS_DELIVERED => 'DELIVERED',
        self::SHIPPING_STATUS_ERR_CONNECT => 'CONNECTION ERROR',
        self::SHIPPING_STATUS_ERR_REGISTER => 'REGISTRATION ERROR',
        self::SHIPPING_STATUS_ERR_LABEL => 'LABEL ERROR',
        self::SHIPPING_STATUS_PROBLEM => 'PROBLEM',
        self::SHIPPING_STATUS_UNRECOGNIZED => 'UNRECOGNIZED STATUS',

    ];

    public function getShippingStatusAttribute()
    {
        return self::SHIPPING_STATUS_STRINGS[$this->shipping_status_code];
    }

    public function labels()
    {
        return $this->hasMany(ShippingLabel::class);
    }

    public function settings()
    {
        return $this->belongsTo(ShippingCompanySetting::class);
    }

    public function company()
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }



    public function logs()
    {
        return $this->belongsToMany(RemoteApiLog::class, 'shipment_remote_api_logs');
    }

}
