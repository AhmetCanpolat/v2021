<?php namespace App\Modules\Api\Requests;

use App\Modules\Api\Validation\ShipmentInfoRules;
use Sfm\Thinc\Validation\ValidatingRequest;

class CreateShipmentRequest extends ValidatingRequest
{
    use ShipmentInfoRules;

    protected $redirectAction = 'ApiController@validationError';
}
