<?php namespace App\Base\Contracts;

use App\Base\Models\RemoteApiLog;

interface RemoteRestRequest
{

    function getStatusCode();

    function getContent();

    function getLog(): ?RemoteApiLog;

    function hasErrors();

    function getErrors();

}
