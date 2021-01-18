<?php namespace App\Base\Abstracts;

abstract class ShippingServiceRequest extends RemoteRestRequest
{
    /**
     * Remote service attached to request
     *
     * @var \App\Base\RemoteService
     */
    protected $shippingService;

    /**
     * Shortcut to user configuration from remote service
     *
     * @var \App\Models\UserShippingProvider
     */
    protected $userConfig;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
        $this->userConfig = $shippingService->getUserConfig();
        parent::__construct();
    }

}
