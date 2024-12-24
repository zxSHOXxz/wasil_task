<?php

namespace App\Services\Booking;

interface BookingValidatorInterface
{
    public function validateBookingDates(int $propertyId, string $startDate, string $endDate, ?int $excludeBookingId = null): array;
    public function getValidationRules(bool $isAdmin = false): array;
}
