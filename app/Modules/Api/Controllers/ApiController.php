<?php namespace App\Modules\Api\Controllers;

use App\Base\Contracts\ShippingServiceLocator;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * The shipping service locator instance
     *
     * @var ShippingServiceLocator
     */
    protected $shippingServiceLocator;

    public function __construct(ShippingServiceLocator $shippingServiceLocator)
    {
        $this->shippingServiceLocator = $shippingServiceLocator;
    }
}
