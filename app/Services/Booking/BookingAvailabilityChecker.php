<?php

namespace App\Services\Booking;

use App\Models\Property;
use Carbon\Carbon;

class BookingAvailabilityChecker implements BookingAvailabilityCheckerInterface
{
    public function getDisabledDates(Property $property): array
    {
        return $property->bookings()
            ->where('status', 'approved')
            ->get()
            ->flatMap(function ($booking) {
                return $this->generateDateRange($booking->start_date, $booking->end_date);
            })
            ->toArray();
    }

    private function generateDateRange(string $startDate, string $endDate): array
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        return $dates;
    }
}
