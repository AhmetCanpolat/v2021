<?php namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCompanySettingCountry extends Model
{
    use SoftDeletes;

    protected $table = 'shipping_company_setting_countries';

    protected $fillable = [
        'sc_setting_id',
        'country_code',
    ];

    protected $with = [
        'country',
    ];

    public function setting()
    {
        return $this->belongsTo(ShippingCompanySetting::class, 'sc_setting_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
