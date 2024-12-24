<?php

namespace App\Services\Booking;

interface BookingDateManagerInterface
{
    public function getDisabledDates(int $propertyId): array;
    public function updateBookingStatus(int $bookingId, string $status): bool;
}
