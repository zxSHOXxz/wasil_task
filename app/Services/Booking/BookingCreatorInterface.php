<?php

namespace App\Services\Booking;

use App\Models\Booking;

interface BookingCreatorInterface
{
    public function create(
        int $propertyId,
        int $userId,
        string $startDate,
        string $endDate,
        float $totalAmount
    ): Booking;
}
