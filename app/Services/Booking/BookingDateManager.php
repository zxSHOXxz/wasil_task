<?php

namespace App\Services\Booking;

use App\Models\Booking;
use Carbon\Carbon;

/**
 * خدمة إدارة تواريخ الحجوزات
 */
class BookingDateManager implements BookingDateManagerInterface
{
    private BookingValidatorInterface $validator;

    public function __construct(BookingValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * الحصول على التواريخ غير المتاحة للحجز
     *
     * @param int $propertyId معرف العقار
     * @return array مصفوفة تحتوي على التواريخ المحجوزة
     */
    public function getDisabledDates(int $propertyId): array
    {
        $bookedDates = [];
        $bookings = Booking::where('property_id', $propertyId)
            ->where('status', 'approved')
            ->where('end_date', '>=', Carbon::today())
            ->get();

        // توليد قائمة بجميع التواريخ المحجوزة
        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->start_date);
            $end = Carbon::parse($booking->end_date);

            while ($start->lt($end)) {
                $bookedDates[] = $start->format('Y-m-d');
                $start->addDay();
            }
        }

        return $bookedDates;
    }

    /**
     * تحديث حالة الحجز
     *
     * @param int $bookingId معرف الحجز
     * @param string $status الحالة الجديدة
     * @return bool نجاح أو فشل العملية
     */
    public function updateBookingStatus(int $bookingId, string $status): bool
    {
        $booking = Booking::findOrFail($bookingId);

        // التحقق من عدم وجود تعارض عند الموافقة على الحجز
        if ($status === 'approved') {
            $errors = $this->validator->validateBookingDates(
                $booking->property_id,
                $booking->start_date,
                $booking->end_date,
                $bookingId
            );

            if (!empty($errors)) {
                return false;
            }
        }

        $booking->status = $status;
        return $booking->save();
    }
}
