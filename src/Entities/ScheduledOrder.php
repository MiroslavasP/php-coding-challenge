<?php

namespace App\Entities;

use Carbon\Carbon;

class ScheduledOrder
{
    private Carbon $deliveryDate;

    private bool $holiday = false;

    private bool $optIn = false;

    private bool $interval = true;

    public function __construct(Carbon $deliveryDate, bool $isInterval)
    {
        $this->deliveryDate = $deliveryDate;
        $this->interval = $isInterval;
    }
    public function getDeliveryDate(): string
    {
        return $this->deliveryDate;
    }
    public function setHoliday(bool $holiday): void
    {
        $this->holiday = $holiday;
    }
    public function setOptIn(bool $optIn): void
    {
        $this->optIn = $optIn;
    }
}
