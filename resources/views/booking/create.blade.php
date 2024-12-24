<x-default-layout>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Booking</h3>
            </div>
            <div class="card-body">
                <livewire:booking.create-booking :property="$property" :startDate="$startDate" />
            </div>
        </div>
    </div>
</x-default-layout>
