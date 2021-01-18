<?php namespace App\Modules\Api\ShippingServices\Mng\Requests;

use App\Base\Contracts\ZoneUpdate as ZoneUpdateContract;
use App\Modules\Api\ShippingServices\Mng\MngService;
use App\Base\Models\ShippingZone;
use DateTimeImmutable;

final class ZoneUpdate extends MngRequest implements ZoneUpdateContract
{

    protected $base_uri;
    protected $path = 'GetCityList';
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


    public function __construct(MngService $shippingService, string $id)
    {
        $userConfig = $shippingService->getUserConfig();
        $this->id = $id;
        $this->userConfig = $shippingService->getUserConfig();
        $zoneconfig  = array("UserName" => $this->userConfig->connection_settings['UserName'],"Password" => $this->userConfig->connection_settings['UserName']);
        $this->options['auth'] = array("UserName" => $this->userConfig->connection_settings['UserName'],"Password" => $this->userConfig->connection_settings['UserName'] );
        $this->options['body'] = json_encode($zoneconfig,JSON_NUMERIC_CHECK);
        parent::__construct($shippingService);
    }


    protected function onSuccess(): void
    {

        $data = json_decode($this->getContent(), true);
        $zones = $data["value"]["alanlar"];
        foreach($zones as $val)
        {
            $shipping = ShippingZone::firstOrNew(['state' => $val['ilce']]);
            $shipping->shipping_id = $this->id;
            $shipping->country = mb_strtoupper($val['ulke'] , 'UTF-8');
            $shipping->city = mb_strtoupper($val['il'], 'UTF-8');
            $shipping->state = mb_strtoupper($val['ilce'], 'UTF-8');
            $shipping->save();
        }
    }

}
