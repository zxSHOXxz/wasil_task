<?php

namespace App\Services\Booking;

use Carbon\Carbon;

class BookingPriceCalculator implements BookingPriceCalculatorInterface
{
    public function calculate(string $startDate, string $endDate, float $pricePerNight): float
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $nights = $start->diffInDays($end);
        return $nights * $pricePerNight;
    }
}
