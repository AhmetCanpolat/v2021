<?php namespace App\Modules\Api\ShippingServices\Mng;

use App\Base\Abstracts\ShippingService;
use App\Base\Contracts\CreateShipment as CreateShipmentContract;
use App\Base\Contracts\GetLabel as GetLabelContract;
use App\Base\Contracts\TrackShipment as TrackShipmentContract;
use App\Base\Errors\CreateShipmentError;
use App\Base\Models\Shipment;
use App\Modules\Api\ShippingServices\Mng\Requests\CreateShipment;
use App\Modules\Api\ShippingServices\Mng\Requests\DeclareCustoms;
use App\Modules\Api\ShippingServices\Mng\Requests\GetAccessToken;
use App\Modules\Api\ShippingServices\Mng\Requests\GetLabel;
use App\Modules\Api\ShippingServices\Mng\Requests\TrackShipment;
use App\Modules\Api\ShippingServices\Mng\Requests\ZoneUpdate as ZoneUpdateShipping;
use Illuminate\Support\Str;
use SoapClient;
use SoapHeader;
use \stdClass;

class MngService extends ShippingService
{
    /**
     * Access token for the request
     *
     * @var string
     */
    protected $accessToken;

    public function getAccessToken(): ?string
    {
        if ($this->accessToken == null) {
            $tokenRequest = new GetAccessToken($this);
            if ($tokenRequest->hasErrors()) {
                $this->addError('Access token request failed', $tokenRequest->getErrors());
                return null;
            } else {
                $this->accessToken = $tokenRequest->getAccessToken();
                return $this->accessToken;
            }
        } else {
            return $this->accessToken;
        }

    }


    public function createShipment(Shipment $shipment): CreateShipmentContract
    {
        $labelData = $this->collectLabelData($this,$shipment->request);
        $shipmentResult = new CreateShipment($this, $labelData);
        return $shipmentResult;
    }

    public function getLabel(string $labelId): GetLabelContract
    {
        return new GetLabel($this, $labelId);
    }

    public function trackShipment(Shipment $shipment): TrackShipmentContract
    {
        return new TrackShipment($this, $shipment->remote_tracking_code . '+' . $shipment->request['receiver']['postalCode']);
    }

    private function collectLabelData($sss,array $requestData): array
    {
        $Mng  = new stdClass();
        $Mng->shipmentId =  Str::uuid();
        $Mng->pChIrsaliyeNo = 1;
        $Mng->pPrKiymet = 1;
        $Mng->pChBarkod = 1;
        $Mng->pChIcerik = 1;
        $Mng->pGonderiHizmetSekli = 1;
        $Mng->pTeslimSekli = 1;
        $Mng->pFlAlSms = 1;
        $Mng->pFlGnSms = 1;
        $Mng->pKargoParcaList = 1;
        $Mng->pAliciMusteriMngNo = 1;
        $Mng->pAliciMusteriBayiNo = 1;
        $Mng->pAliciMusteriAdi = 1;
        $Mng->pChSiparisNo = 1;
        $Mng->pLuOdemeSekli = 1;
        $Mng->pFlAdresFarkli = 1;
        $Mng->pChIl = 1;
        $Mng->pChIlce = 1;
        $Mng->pChAdres = 1;
        $Mng->pChSemt = 1;
        $Mng->pChMahalle = 1;
        $Mng->pChMeydanBulvar = 1;
        $Mng->pChCadde = 1;
        $Mng->pChSokak = 1;
        $Mng->pChTelEv = 1;
        $Mng->pChTelCep = 1;
        $Mng->pChTelIs = 1;
        $Mng->pChFax = 1;
        $Mng->pChEmail = 1;
        $Mng->pChVergiDairesi = 1;
        $Mng->pChVergiNumarasi = 1;
        $Mng->pFlKapidaOdeme = 1;
        $Mng->pMalBedeliOdemeSekli = 1;
        $Mng->pPlatformKisaAdi = 1;
        $Mng->pPlatformSatisKodu = 1;
        $Mng->pKullaniciAdi = 1;
        $Mng->pSifre = 1;
        return (array)$Mng;

    }

    public function updateZone($id)
    {
           $zoneupdate = new ZoneUpdateShipping($this,$id);
           return $zoneupdate;
    }



}
