<?php

namespace App\Base\Models;

use App\Base\Traits\LocalizedTimestamps;
use Illuminate\Database\Eloquent\Model;

class RemoteApiLog extends Model
{
    use LocalizedTimestamps;
}
