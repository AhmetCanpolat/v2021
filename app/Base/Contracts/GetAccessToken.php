<?php namespace App\Base\Contracts;

interface GetAccessToken extends RemoteRestRequest
{
    
    public function getAccessToken(): string;
    
    public function getAccessTokenExpiration(): int;
    
    public function getRefreshToken(): string;
    
    public function getRefreshTokenExpiration(): int;
    
    public function getAccountNumbers(): array;
    
}