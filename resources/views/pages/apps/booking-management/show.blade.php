<x-default-layout>
    @section('title')
        Booking Details
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('booking-management.bookings.show', $booking) }}
    @endsection

    <!--begin::Layout-->
    <div class="d-flex flex-column flex-lg-row">

    </div>
    <!--end::Layout-->

</x-default-layout>
