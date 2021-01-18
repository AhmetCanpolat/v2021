<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingZone extends Model
{

    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'shipping_id',
        'country',
        'city',
        'state',
    ];


    public function company()
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_id');
    }


}
