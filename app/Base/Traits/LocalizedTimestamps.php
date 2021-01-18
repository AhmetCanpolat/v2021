<?php namespace App\Base\Traits;

use DateTime;
use DateTimeZone;

trait LocalizedTimestamps
{
    public function getCreatedAtAttribute(string $date): string
    {
        return $this->localizeDate($date);
    }

    public function getUpdatedAtAttribute(string $date): string
    {
        return $this->localizeDate($date);
    }

    private function localizeDate(string $date): string
    {
        return (new DateTime($date))->setTimezone(new DateTimeZone('Europe/Istanbul'))->format(DATE_RSS);
    }

}
