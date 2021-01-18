<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCompany extends Model
{

    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'settings_fields',
        'zone_update_link',
    ];

}
