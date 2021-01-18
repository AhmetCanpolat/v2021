<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uri',
        'secret',
        'type',
    ];

    const WEBHOOK_TYPE_EVENT = 10;
    const WEBHOOK_TYPE_STATUS = 20;
    const WEBHOOK_TYPE_DELIVERED = 30;

    const WEBHOOK_TYPE_STRINGS = [
        self::WEBHOOK_TYPE_EVENT => 'EVENT CHANGE WEBHOOK',
        self::WEBHOOK_TYPE_STATUS => 'STATUS CHANGE WEBHOOK',
        self::WEBHOOK_TYPE_DELIVERED => 'DELIVERED WEBHOOK',
    ];

    public function companySetting()
    {
        return $this->belongsTo(ShippingCompanySetting::class, 'setting_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
