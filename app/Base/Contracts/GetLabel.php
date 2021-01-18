<?php namespace App\Base\Contracts;

interface GetLabel extends RemoteRestRequest
{

    public function getPdf(): string;

}
