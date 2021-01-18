<?php

namespace App\Base\Models;

use Illuminate\Database\Eloquent\Model;

class RabbitMqConsumerLog extends Model
{
    protected $fillable = ['publish_data'];

    const NO_ERROR = 0;
    const NO_SHIPMENT_ERROR = 20;
    const REMOTE_CONNECTION_ERROR = 30;
    const REMOTE_REGISTRATION_ERROR = 40;
    const LABEL_EXISTS_ERROR = 50;
    const LABEL_DOWNLOAD_ERROR = 60;
    const LABEL_SAVE_ERROR = 70;
    const TRACKING_STOPPED_ERROR = 80;
    const UNREADY_FOR_TRACKING_ERROR = 90;

    const ERROR_STRINGS = [
        self::NO_ERROR => 'NO ERROR',
        self::NO_SHIPMENT_ERROR => 'SHIPMENT NOT FOUND',
        self::REMOTE_CONNECTION_ERROR => 'REMOTE CONNECTION FAILED',
        self::REMOTE_REGISTRATION_ERROR => 'REMOTE SHIPMENT REGISTRATION FAILED',
        self::LABEL_EXISTS_ERROR => 'LABEL ID ALREADY EXISTS',
        self::LABEL_DOWNLOAD_ERROR => 'LABEL DOWNLOAD FAILED',
        self::LABEL_SAVE_ERROR => 'COULD NOT WRITE LABEL TO DISK',
        self::TRACKING_STOPPED_ERROR => 'CANNOT TRACK SHIPMENT ',
        self::UNREADY_FOR_TRACKING_ERROR => 'CANNOT TRACK UNPREPARED PACKAGE',
    ];
}
