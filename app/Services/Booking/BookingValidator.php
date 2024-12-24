<?php

namespace App\Services\Booking;

use App\Models\Booking;
use Carbon\Carbon;

/**
 * خدمة التحقق من صحة بيانات الحجز
 */
class BookingValidator implements BookingValidatorInterface
{
    /**
     * التحقق من صحة تواريخ الحجز
     *
     * @param int $propertyId معرف العقار
     * @param string $startDate تاريخ البداية
     * @param string $endDate تاريخ النهاية
     * @param int|null $excludeBookingId معرف الحجز المستثنى من التحقق (اختياري)
     * @return array مصفوفة تحتوي على أخطاء التحقق إن وجدت
     */
    public function validateBookingDates(int $propertyId, string $startDate, string $endDate, ?int $excludeBookingId = null): array
    {
        $errors = [];
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // التحقق من أن تاريخ النهاية بعد تاريخ البداية
        if ($startDate->gt($endDate)) {
            $errors['end_date'] = __('Check-out date must be after check-in date');
            return $errors;
        }

        // بناء استعلام للتحقق من تعارض التواريخ
        $query = Booking::where('property_id', $propertyId)
            ->where('status', 'approved');

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        // التحقق من تعارض التواريخ مع الحجوزات الموجودة
        $hasConflict = $query->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<', $endDate)
                        ->where('end_date', '>=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '>=', $startDate)
                        ->where('end_date', '<=', $endDate);
                });
            })
            ->exists();

        if ($hasConflict) {
            $errors['start_date'] = __('Selected dates conflict with an existing booking');
            $errors['end_date'] = __('Selected dates conflict with an existing booking');
        }

        return $errors;
    }

    /**
     * الحصول على قواعد التحقق من صحة البيانات
     *
     * @param bool $isAdmin هل المستخدم مدير
     * @return array مصفوفة تحتوي على قواعد التحقق
     */
    public function getValidationRules(bool $isAdmin = false): array
    {
        $rules = [
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:pending,approved,rejected'
        ];

        if ($isAdmin) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        return $rules;
    }
}
