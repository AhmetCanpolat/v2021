<?php namespace App\Modules\Api\ShippingServices\Mng\Requests;

use App\Modules\Api\ShippingServices\Mng\MngService;

final class DeclareCustoms extends MngRequest
{
    protected $base_uri;
    protected $path = 'setOrder';
    protected $method = 'soap';
    protected $options = [
        'auth' => [
            'UserName' => 'application/json',
            'Password' => 'application/json',
        ],

        'headers' => [
            'Authentication' => 'Authentication',
            'url' => 'http://tempuri.org/',
        ],
    ];

    private $accessToken;
    private $payload;

    public function __construct(MngService $shippingService, array $payload)
    {
        $this->accessToken = $shippingService->getAccessToken();
        $this->userConfig = $shippingService->getUserConfig();
        $this->payload = $payload;
        //$this->payload['accountId'] = $this->userConfig->connection_settings['account_id'];
        //$this->options['headers']['Authorization'] = 'Bearer ' . $this->accessToken;
        $this->options['auth'] = array("UserName" => $this->userConfig->connection_settings['UserName'],"Password" => $this->userConfig->connection_settings['UserName'] );
        $this->options['body'] = json_encode($this->payload,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        parent::__construct($shippingService);
    }

}
