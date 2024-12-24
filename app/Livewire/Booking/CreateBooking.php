<?php

namespace App\Livewire\Booking;

use App\Models\Property;
use App\Models\Booking;
use App\Services\Booking\BookingPriceCalculatorInterface;
use App\Services\Booking\BookingValidatorInterface;
use App\Services\Booking\BookingDateManagerInterface;
use App\Services\Booking\BookingCreatorInterface;
use Livewire\Component;
use Carbon\Carbon;

/**
 * مكون Livewire لإنشاء حجز جديد
 * يستخدم في صفحة تفاصيل العقار للحجز المباشر
 */
class CreateBooking extends Component
{
    /**
     * العقار المراد الحجز له
     */
    public Property $property;

    /**
     * تواريخ الحجز
     */
    public ?string $startDate = null;    // تاريخ بداية الحجز
    public ?string $endDate = null;      // تاريخ نهاية الحجز
    public float $totalPrice = 0;        // السعر الإجمالي للحجز

    /**
     * الخدمات المستخدمة في المكون
     */
    private BookingPriceCalculatorInterface $priceCalculator;    // حساب السعر
    private BookingValidatorInterface $validator;                // التحقق من صحة البيانات
    private BookingDateManagerInterface $dateManager;            // إدارة التواريخ
    private BookingCreatorInterface $bookingCreator;            // إنشاء الحجز

    /**
     * تهيئة الخدمات المطلوبة
     */
    public function boot(
        BookingPriceCalculatorInterface $priceCalculator,
        BookingValidatorInterface $validator,
        BookingDateManagerInterface $dateManager,
        BookingCreatorInterface $bookingCreator
    ) {
        $this->priceCalculator = $priceCalculator;
        $this->validator = $validator;
        $this->dateManager = $dateManager;
        $this->bookingCreator = $bookingCreator;
    }

    /**
     * تهيئة المكون عند تحميله
     *
     * @param Property $property العقار المراد الحجز له
     */
    public function mount(Property $property): void
    {
        $this->property = $property;
        $this->startDate = date('Y-m-d');  // تعيين تاريخ اليوم كتاريخ بداية افتراضي
    }

    /**
     * معالجة تحديث تاريخ البداية
     */
    public function updatedStartDate(): void
    {
        $this->calculatePrice();
    }

    /**
     * معالجة تحديث تاريخ النهاية
     */
    public function updatedEndDate(): void
    {
        $this->calculatePrice();
    }

    /**
     * حساب السعر الإجمالي للحجز
     */
    private function calculatePrice(): void
    {
        if ($this->startDate && $this->endDate) {
            $this->totalPrice = $this->priceCalculator->calculate(
                $this->startDate,
                $this->endDate,
                $this->property->price_per_night
            );
        }
    }

    /**
     * الحصول على التواريخ غير المتاحة للحجز
     *
     * @return array مصفوفة تحتوي على التواريخ المحجوزة
     */
    public function getDisabledDatesProperty(): array
    {
        return $this->dateManager->getDisabledDates($this->property->id);
    }

    /**
     * حفظ الحجز الجديد
     */
    public function save()
    {
        // التحقق من صحة البيانات
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        // التحقق من عدم وجود تعارض في التواريخ
        $errors = $this->validator->validateBookingDates(
            $this->property->id,
            $this->startDate,
            $this->endDate
        );

        if (!empty($errors)) {
            foreach ($errors as $field => $error) {
                $this->addError($field, $error);
            }
            return;
        }

        // إنشاء الحجز
        $this->bookingCreator->create(
            $this->property->id,
            auth()->id(),
            $this->startDate,
            $this->endDate,
            $this->totalPrice
        );

        // إعادة التوجيه مع رسالة نجاح
        return redirect()
            ->route('booking-management.bookings.index')
            ->with('success', __('Booking created successfully!'));
    }

    /**
     * عرض واجهة المكون
     */
    public function render()
    {
        return view('livewire.booking.create-booking');
    }
}
