<?php namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCompanySetting extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'shipping_company_id',
        'event_webhook',
        'status_webhook',
        'delivery_webhook',
        'connection_settings',
    ];

    protected $casts = [
        'connection_settings' => 'json',
    ];

    public function company()
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
