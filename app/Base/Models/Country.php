<?php namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name'];

    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

}
