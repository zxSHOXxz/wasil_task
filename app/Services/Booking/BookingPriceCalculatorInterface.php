<?php

namespace App\Services\Booking;

interface BookingPriceCalculatorInterface
{
    public function calculate(string $startDate, string $endDate, float $pricePerNight): float;
}
