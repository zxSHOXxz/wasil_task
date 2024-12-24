<x-default-layout>

    @section('title')
        Bookings
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('booking-management.bookings.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-booking-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search booking"
                        id="mySearchInput" />
                </div>
            </div>

            @can('create bookings')
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-booking-table-toolbar="base">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_booking">
                            {!! getIcon('plus', 'fs-2', '', 'i') !!}
                            Add Booking </button>
                    </div>
                    <livewire:booking.add-booking-modal></livewire:booking.add-booking-modal>
                </div>
            @endcan

        </div>

        <div class="card-body py-4">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function() {
                window.LaravelDataTables['booking-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function() {
                Livewire.on('success', function() {
                    $('#kt_modal_add_booking').modal('hide');
                    window.LaravelDataTables['booking-table'].ajax.reload();
                });
            });
            $('#kt_modal_add_booking').on('hidden.bs.modal', function() {
                Livewire.dispatch('new_booking');
            });
        </script>
    @endpush

</x-default-layout>
