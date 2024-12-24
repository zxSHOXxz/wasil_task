<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use App\Services\Booking\BookingPriceCalculatorInterface;
use App\Services\Booking\BookingValidatorInterface;
use App\Services\Booking\BookingDateManagerInterface;
use App\Services\Booking\BookingCreatorInterface;
use Livewire\Component;
use Carbon\Carbon;

/**
 * مكون Livewire لإدارة نافذة إضافة الحجوزات
 */
class AddBookingModal extends Component
{
    /**
     * بيانات الحجز
     */
    public $user_id;              // معرف المستخدم
    public $property_id;          // معرف العقار
    public $start_date;           // تاريخ بداية الحجز
    public $end_date;             // تاريخ نهاية الحجز
    public $total_amount = 0;     // المبلغ الإجمالي
    public $status = 'pending';   // حالة الحجز
    public $min_date;             // أقل تاريخ مسموح به للحجز
    public $disabledDates = [];   // التواريخ غير المتاحة للحجز

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
     */
    public function mount()
    {
        $this->min_date = Carbon::today()->format('Y-m-d');
        if (!auth()->user()->hasRole('admin')) {
            $this->user_id = auth()->id();
        }
    }

    /**
     * قواعد التحقق من صحة البيانات
     */
    protected function rules()
    {
        return $this->validator->getValidationRules(auth()->user()->hasRole('admin'));
    }

    /**
     * الأحداث التي يستمع لها المكون
     */
    protected $listeners = [
        'delete_booking' => 'deleteBooking',
        'update_status' => 'updateStatus'
    ];

    /**
     * حذف حجز
     */
    public function deleteBooking($bookingId)
    {
        Booking::findOrFail($bookingId)->delete();
        $this->dispatch('success', __('Booking deleted successfully'));
    }

    /**
     * تحديث حالة الحجز
     */
    public function updateStatus($id, $value)
    {
        if ($this->dateManager->updateBookingStatus($id, $value)) {
            $this->dispatch('success', __('Booking status updated successfully'));
        } else {
            $this->dispatch('error', __('Cannot approve booking. The property is already booked during this period.'));
        }
    }

    /**
     * معالجة تحديث العقار المحدد
     */
    public function updatedPropertyId($value)
    {
        if ($value) {
            $this->loadDisabledDates();
            $this->calculateTotalAmount();
        } else {
            $this->reset(['start_date', 'end_date', 'total_amount']);
        }
    }

    /**
     * معالجة تحديث تاريخ البداية
     */
    public function updatedStartDate($value)
    {
        if ($value) {
            $this->validateBookingDates();
            $this->calculateTotalAmount();
        }
    }

    /**
     * معالجة تحديث تاريخ النهاية
     */
    public function updatedEndDate($value)
    {
        if ($value) {
            $this->validateBookingDates();
            $this->calculateTotalAmount();
        }
    }

    /**
     * التحقق من صحة تواريخ الحجز
     */
    protected function validateBookingDates()
    {
        if ($this->property_id && $this->start_date && $this->end_date) {
            $errors = $this->validator->validateBookingDates(
                $this->property_id,
                $this->start_date,
                $this->end_date
            );

            foreach ($errors as $field => $error) {
                $this->addError($field, $error);
            }

            if (isset($errors['start_date']) || isset($errors['end_date'])) {
                $this->reset(['start_date', 'end_date']);
            }
        }
    }

    /**
     * تحميل التواريخ غير المتاحة للحجز
     */
    protected function loadDisabledDates()
    {
        if ($this->property_id) {
            $bookedDates = $this->dateManager->getDisabledDates($this->property_id);
            $this->dispatch('updateDisabledDates', disabledDates: $bookedDates);
            $this->disabledDates = $bookedDates;
        }
    }

    /**
     * حساب المبلغ الإجمالي للحجز
     */
    protected function calculateTotalAmount()
    {
        try {
            if ($this->property_id && $this->start_date && $this->end_date) {
                $property = Property::find($this->property_id);
                if ($property) {
                    $this->total_amount = $this->priceCalculator->calculate(
                        $this->start_date,
                        $this->end_date,
                        $property->price_per_night
                    );
                } else {
                    $this->total_amount = 0;
                }
            } else {
                $this->total_amount = 0;
            }
        } catch (\Exception $e) {
            $this->total_amount = 0;
        }
    }

    /**
     * حفظ الحجز
     */
    public function save()
    {
        $this->validate();
        $this->validateBookingDates();

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        if (!auth()->user()->hasRole('admin')) {
            $this->user_id = auth()->id();
        }

        $this->bookingCreator->create(
            (int) $this->property_id,
            (int) $this->user_id,
            $this->start_date,
            $this->end_date,
            $this->total_amount
        );

        $this->reset(['property_id', 'start_date', 'end_date', 'total_amount']);
        if (auth()->user()->hasRole('admin')) {
            $this->reset(['user_id']);
        }

        $this->dispatch('success', __('Booking created successfully'));
        $this->dispatch('hideModal');
    }

    /**
     * عرض واجهة المكون
     */
    public function render()
    {
        return view('livewire.booking.add-booking-modal', [
            'properties' => Property::where('status', 'available')->get(),
            'users' => auth()->user()->hasRole('admin')
                ? User::whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })->get()
                : collect([auth()->user()]),
        ]);
    }
}
