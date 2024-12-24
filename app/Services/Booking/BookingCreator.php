<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Property;

/**
 * خدمة إنشاء الحجوزات
 */
class BookingCreator implements BookingCreatorInterface
{
    /**
     * إنشاء حجز جديد
     *
     * @param int $propertyId معرف العقار
     * @param int $userId معرف المستخدم
     * @param string $startDate تاريخ بداية الحجز
     * @param string $endDate تاريخ نهاية الحجز
     * @param float $totalAmount المبلغ الإجمالي
     * @return Booking
     */
    public function create(
        int $propertyId,
        int $userId,
        string $startDate,
        string $endDate,
        float $totalAmount
    ): Booking {
        return Booking::create([
            'property_id' => $propertyId,
            'user_id' => $userId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_amount' => $totalAmount,
            'status' => 'pending'
        ]);
    }
}
