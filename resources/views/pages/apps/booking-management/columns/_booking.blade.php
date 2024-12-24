<!--begin:: Avatar -->
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="{{ route('booking-management.bookings.show', $booking) }}">
        @if ($booking->avatar)
            <div class="symbol-label">
                <img src="{{ $booking->getConvertedImage() }}" class="w-100" />
            </div>
        @else
            <div
                class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $booking->name) }}">
                {{ substr($booking->name, 0, 1) }}
            </div>
        @endif
    </a>
</div>
<!--end::Avatar-->
<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{ route('booking-management.bookings.show', $booking) }}" class="text-gray-800 text-hover-primary mb-1">
        {{ $booking->name }}
    </a>
    <span>{{ $booking->email }}</span>
</div>
<!--begin::User details-->
