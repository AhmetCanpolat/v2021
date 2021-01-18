<?php namespace App\Modules\Api\ShippingServices\Mng\Requests;

use App\Base\Abstracts\ShippingServiceRequest;
use App\Modules\Api\ShippingServices\Mng\MngService;

abstract class MngRequest extends ShippingServiceRequest{

    public function __construct(MngService $shippingService)
    {
         $this->base_uri = "http://service.mngkargo.com.tr/tservis/musterikargosiparis.asmx";
        parent::__construct($shippingService);
    }

}
