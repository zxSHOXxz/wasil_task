<?php

namespace App\Services\Booking;

use App\Models\Property;

interface BookingAvailabilityCheckerInterface
{
    public function getDisabledDates(Property $property): array;
}
