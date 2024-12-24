<x-default-layout>
    @section('title')
        Property Details
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('property-management.properties.show', $property) }}
    @endsection

    <!--begin::Layout-->
    <div class="d-flex flex-column flex-lg-row">
        
    </div>
    <!--end::Layout-->

</x-default-layout>
