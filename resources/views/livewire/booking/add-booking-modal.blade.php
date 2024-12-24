<div wire:ignore.self class="modal fade" tabindex="-1" id="kt_modal_add_booking">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Add Booking</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form wire:submit="save" id="kt_modal_add_booking_form" class="form">
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_booking_scroll">
                        @if (auth()->user()->hasRole('admin'))
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">User</label>
                                <select wire:model="user_id" class="form-select form-select-solid fw-bold">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        @if (!$property_id)
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Property</label>
                                <select wire:model.live="property_id" class="form-select form-select-solid fw-bold">
                                    <option value="">Select Property</option>
                                    @foreach ($properties as $property)
                                        <option value="{{ $property->id }}">{{ $property->name }} -
                                            ${{ number_format($property->price_per_night, 2) }}/night</option>
                                    @endforeach
                                </select>
                                @error('property_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Property</label>
                                <div class="form-control form-control-solid mb-3 mb-lg-0 bg-light">
                                    {{ $properties->where('id', $property_id)->first()->name }} -
                                    ${{ number_format($properties->where('id', $property_id)->first()->price_per_night, 2) }}/night
                                </div>
                            </div>
                        @endif

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Check In Date</label>
                            <input type="text" wire:model.live="start_date" id="start_date"
                                class="form-control form-control-solid mb-3 mb-lg-0 flatpickr-input">
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Check Out Date</label>
                            <input type="text" wire:model.live="end_date" id="end_date"
                                class="form-control form-control-solid mb-3 mb-lg-0 flatpickr-input">
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Total Amount</label>
                            <div class="form-control form-control-solid mb-3 mb-lg-0 bg-light">
                                ${{ number_format($total_amount, 2) }}
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Status</label>
                            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0" value="Pending"
                                disabled>
                            <div class="text-muted fs-7">New bookings are automatically set to pending status</div>
                        </div>
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <style>
        .flatpickr-calendar {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            border-radius: 10px !important;
        }

        .flatpickr-day {
            border-radius: 6px !important;
            font-weight: 500 !important;
            font-size: 14px !important;
            transition: all 0.2s ease !important;
        }

        .flatpickr-day.selected {
            background: #009ef7 !important;
            border-color: #009ef7 !important;
        }

        .flatpickr-day.disabled {
            background-color: #fff3f3 !important;
            color: #ff6b6b !important;
            text-decoration: line-through !important;
            cursor: not-allowed !important;
            opacity: 0.7 !important;
        }

        .flatpickr-day:hover {
            background: #e8f6ff !important;
            border-color: #e8f6ff !important;
        }

        .flatpickr-day.disabled:hover {
            background-color: #fff3f3 !important;
            border-color: transparent !important;
        }

        .flatpickr-months .flatpickr-month {
            background: #009ef7 !important;
            color: white !important;
            fill: white !important;
            border-radius: 10px 10px 0 0 !important;
        }

        .flatpickr-current-month {
            padding-top: 15px !important;
            font-size: 16px !important;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            top: 10px !important;
            padding: 5px !important;
            fill: white !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush

@script
    <script>
        let startDatePicker;
        let endDatePicker;
        let disabledDates = [];

        function initializeDatePickers() {
            const commonConfig = {
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: disabledDates,
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ["أحد", "اثن", "ثلا", "أرب", "خمي", "جمع", "سبت"],
                        longhand: ["الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت"]
                    },
                    months: {
                        shorthand: ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر",
                            "أكتوبر", "نوفمبر", "ديسمبر"
                        ],
                        longhand: ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر",
                            "أكتوبر", "نوفمبر", "ديسمبر"
                        ]
                    }
                },
                onChange: function(selectedDates, dateStr) {
                    $wire.set(this.element.id, dateStr);
                },
                onMonthChange: function() {
                    this.redraw();
                }
            };

            // Initialize start date picker
            startDatePicker = flatpickr("#start_date", {
                ...commonConfig,
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates[0]) {
                        endDatePicker.set('minDate', dateStr);
                        $wire.set('start_date', dateStr);
                    }
                }
            });

            // Initialize end date picker
            endDatePicker = flatpickr("#end_date", {
                ...commonConfig,
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates[0]) {
                        startDatePicker.set('maxDate', dateStr);
                        $wire.set('end_date', dateStr);
                    }
                }
            });
        }

        // Initialize on page load
        initializeDatePickers();

        // Listen for property changes
        $wire.on('updateDisabledDates', ({
            disabledDates: dates
        }) => {
            disabledDates = dates;

            if (startDatePicker && endDatePicker) {
                startDatePicker.set('disable', dates);
                endDatePicker.set('disable', dates);

                // Clear dates if they're now disabled
                if (startDatePicker.selectedDates.length && dates.includes(startDatePicker.input.value)) {
                    startDatePicker.clear();
                    $wire.set('start_date', null);
                }
                if (endDatePicker.selectedDates.length && dates.includes(endDatePicker.input.value)) {
                    endDatePicker.clear();
                    $wire.set('end_date', null);
                }
            }
        });

        // Clean up on modal hide
        $wire.on('hideModal', () => {
            if (startDatePicker && endDatePicker) {
                startDatePicker.clear();
                endDatePicker.clear();
            }
        });
    </script>
@endscript
