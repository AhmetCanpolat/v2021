<?php namespace App\Modules\Api\ShippingServices\Mng\Requests;

use App\Base\Contracts\GetLabel as GetLabelContract;
use App\Modules\Api\ShippingServices\Mng\MngService;

final class GetLabel extends MngRequest implements GetLabelContract
{

    protected $base_uri;
    protected $method = 'GetBarcode';
    protected $type = 'soap';
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
     private $pdf = '';

    /**
     * CreateShipment constructor.
     * @param Providers $provider
     * @param $payload
     * @param $accessToken
     */
    public function __construct(MngService $remoteService, string $labelId)
    {
        $userConfig = $remoteService->getUserConfig();
        $this->userConfig = $remoteService->getUserConfig();
        $trakingconfig  = array("UserName" => $this->userConfig->connection_settings['UserName'],"Password" => $this->userConfig->connection_settings['UserName'],"integrationCode" => $labelId);
        $this->options['auth'] = array("UserName" => $this->userConfig->connection_settings['UserName'],"Password" => $this->userConfig->connection_settings['UserName'] );
        $this->options['body'] = json_encode($trakingconfig,JSON_NUMERIC_CHECK);
        parent::__construct($remoteService);
    }

    protected function onSuccess(): void
    {
        $content = json_decode($this->getContent(), true);
        $this->setPdf($content["value"][0]["barkodText"]);
    }

    /**
     * @return string
     */
    public function getPdf(): string
    {
        return $this->pdf;
    }

    /**
     * @param string $pdf
     * @return GetLabel
     */
    private function setPdf(string $pdf): GetLabel
    {
        $this->pdf = $pdf;

        return $this;
    }

}
