<?php namespace App\Modules\Api\ShippingServices\Mng\Requests;

use App\Base\Contracts\GetAccessToken as GetAccessTokenContract;
use App\Modules\Api\ShippingServices\Mng\MngService;

final class GetAccessToken extends MngRequest implements GetAccessTokenContract
{


    protected $base_uri;
    protected $path = 'authenticate/api-key';
    protected $method = 'post';
    protected $options = [
        'headers' => [
            'content-type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ];

    /**
     * The access token retrieved from the request
     *
     * @var string
     */
    private $accessToken;
    private $accessTokenExpiration = 0;
    private $refreshToken = '';
    private $refreshTokenExpiration = 0;
    private $accountNumbers = [];

    public function __construct(MngService $shippingService)
    {
        $this->userConfig = $shippingService->getUserConfig();
        $this->options['body'] = json_encode([
            'userId' => $this->userConfig->connection_settings['KullaniciAdi'],
            'key' => $this->userConfig->connection_settings['Sifre'],
        ]);
        parent::__construct($shippingService);
    }

    protected function onSuccess(): void
    {
        $content = json_decode($this->getContent());
        $this->setAccessToken($content->accessToken)
            ->setAccessTokenExpiration($content->accessTokenExpiration)
            ->setAccountNumbers($content->accountNumbers)
            ->setRefreshToken($content->refreshToken)
            ->setRefreshTokenExpiration($content->refreshTokenExpiration);
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return GetAccessToken
     */
    private function setAccessToken(string $accessToken): GetAccessToken
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return int
     */
    public function getAccessTokenExpiration(): int
    {
        return $this->accessTokenExpiration;
    }

    /**
     * @param int $accessTokenExpiration
     * @return GetAccessToken
     */
    private function setAccessTokenExpiration(int $accessTokenExpiration): GetAccessToken
    {
        $this->accessTokenExpiration = $accessTokenExpiration;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return GetAccessToken
     */
    private function setRefreshToken(string $refreshToken): GetAccessToken
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return int
     */
    public function getRefreshTokenExpiration(): int
    {
        return $this->refreshTokenExpiration;
    }

    /**
     * @param int $refreshTokenExpiration
     * @return GetAccessToken
     */
    private function setRefreshTokenExpiration(int $refreshTokenExpiration): GetAccessToken
    {
        $this->refreshTokenExpiration = $refreshTokenExpiration;
        return $this;
    }

    /**
     * @return array
     */
    public function getAccountNumbers(): array
    {
        return $this->accountNumbers;
    }

    /**
     * @param array $accountNumbers
     * @return GetAccessToken
     */
    private function setAccountNumbers(array $accountNumbers): GetAccessToken
    {
        $this->accountNumbers = $accountNumbers;
        return $this;
    }

}
