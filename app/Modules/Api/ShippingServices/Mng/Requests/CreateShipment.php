<?php namespace App\Modules\Api\ShippingServices\Mng\Requests;

use App\Base\Contracts\CreateShipment as CreateShipmentContract;
use App\Modules\Api\ShippingServices\Mng\MngService;

final class CreateShipment extends MngRequest implements CreateShipmentContract
{


    protected $base_uri;
    protected $method = 'SetOrder';
    protected $type = 'soap';
    protected $options = [
        'auth' => [
            'KullaniciAdi' => 'application/json',
            'Sifre' => 'application/json',
        ],

        'headers' => [
            'Authentication' => 'Authentication',
            'url' => 'http://tempuri.org/',
        ],
    ];

    private $accessToken;
    private $payload;
    private $shipmentId = '';
    private $pieces = [];
    private $trackerCode = '';
    private $labelId = '';

    public function __construct(MngService $shippingService, array $payload)
    {
        $this->payload = $payload;
        $this->userConfig = $shippingService->getUserConfig();
        $this->options['auth'] = array("pKullaniciAdi" => $this->userConfig->connection_settings['UserName'],"pSifre" => $this->userConfig->connection_settings['Password'] );
        $data =  array("orderInfo"=>array("SiparisGirisiDetayliV3"=>$this->payload),"pKullaniciAdi"=>$this->userConfig->connection_settings['UserName'],"pSifre"=>$this->userConfig->connection_settings['Password']);
        $this->options['body'] = json_encode($data);
        parent::__construct($shippingService);
    }

    protected function onSuccess(): void
    {
         $content = $this->getContent();
         $shippimentData = json_decode($this->options['body']);

         $this->setPieces(array())
            ->setShipmentId($shippimentData->orderInfo->Order->shipmentId)
            ->setTrackingCode($content->InvoiceKey)
            ->setLabelId($content->InvoiceKey);
    }

    /**
     * @return string
     */
    public function getShipmentId(): string
    {
        return $this->shipmentId;
    }

    /**
     * @param string $shipmentId
     * @return CreateShipment
     */
    private function setShipmentId(string $shipmentId): CreateShipment
    {
        $this->shipmentId = $shipmentId;

        return $this;
    }

    /**
     * @return array
     */
    public function getPieces(): array
    {
        return $this->pieces;
    }

    /**
     * @param array $pieces
     * @return CreateShipment
     */
    private function setPieces(array $pieces): CreateShipment
    {
        $this->pieces = $pieces;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrackingCode(): string
    {
        return $this->trackerCode;
    }

    /**
     * @param string $trackerCode
     * @return CreateShipment
     */
    private function setTrackingCode(string $trackerCode): CreateShipment // TODO Burası Sorulacak
    {
        $this->trackerCode = $trackerCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabelId(): string
    {
        return $this->labelId;
    }

    /**
     * @param string $labelId
     * @return CreateShipment
     */
    private function setLabelId(string $labelId): CreateShipment
    {
        $this->labelId = $labelId;

        return $this;
    }

}
