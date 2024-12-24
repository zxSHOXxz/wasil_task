<div>
    <form wire:submit.prevent="save">
        <div class="row mb-5">
            <div class="col-md-6">
                <label class="form-label">Property</label>
                <input type="text" class="form-control" value="{{ $property->name }}" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Price per night</label>
                <input type="text" class="form-control" value="${{ number_format($property->price_per_night, 2) }}" disabled>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <label class="form-label">Start Date</label>
                <input type="text" class="form-control flatpickr" wire:model.live="startDate" 
                    min="{{ date('Y-m-d') }}" 
                    x-data
                    x-init="flatpickr($el, {
                        minDate: 'today',
                        disable: @js($this->disabledDates),
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates, dateStr) {
                            @this.set('startDate', dateStr);
                        }
                    })"
                >
                @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">End Date</label>
                <input type="text" class="form-control flatpickr" wire:model.live="endDate"
                    min="{{ $startDate }}"
                    x-data
                    x-init="flatpickr($el, {
                        minDate: @entangle('startDate'),
                        disable: @js($this->disabledDates),
                        dateFormat: 'Y-m-d',
                        onChange: function(selectedDates, dateStr) {
                            @this.set('endDate', dateStr);
                        }
                    })"
                >
                @error('endDate') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <label class="form-label">Total Price</label>
                <input type="text" class="form-control" value="${{ number_format($totalPrice, 2) }}" disabled>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="ki-duotone ki-calendar-add fs-2 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                </i>
                Create Booking
            </button>
        </div>
    </form>
</div>
