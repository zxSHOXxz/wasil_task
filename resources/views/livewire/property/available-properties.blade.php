<div>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input wire:model.live="search" type="text" class="form-control form-control-solid w-250px ps-13"
                        placeholder=" Search ... " />
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <div class="d-flex align-items-center">
                    <label class="fs-6 fw-semibold me-3">Date</label>
                    <input type="date" wire:model.live="selected_date" min="{{ now()->format('Y-m-d') }}"
                        class="form-control form-control-solid" />
                </div>
            </div>
            <!--end::Card toolbar-->
        </div>

        <div class="card-body py-4">
            <div class="row g-6 g-xl-9">
                @forelse($properties as $property)
                    <div class="col-md-6 col-xl-4">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100 ribbon ribbon-top">
                            <div class="ribbon-label bg-success"> Available for booking </div>

                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title d-flex flex-column flex-grow-1">
                                    <span class="fs-2x fw-bold text-dark mb-2">{{ $property->name }}</span>
                                    <span
                                        class="text-gray-400 fw-semibold fs-6">{{ Str::limit($property->description, 80) }}</span>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Image slider-->
                                <div class="property-gallery mb-8">
                                    @php
                                        $images = json_decode($property->images);
                                        $carouselId = 'propertyCarousel' . $property->id;
                                    @endphp

                                    <!--begin::Carousel-->
                                    <div id="{{ $carouselId }}" class="carousel slide" data-bs-ride="carousel">
                                        <!-- Indicators/dots -->
                                        <div class="carousel-indicators">
                                            @foreach ($images as $index => $image)
                                                <button type="button" data-bs-target="#{{ $carouselId }}"
                                                    data-bs-slide-to="{{ $index }}"
                                                    class="{{ $index === 0 ? 'active' : '' }}"
                                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                    aria-label="Slide {{ $index + 1 }}">
                                                </button>
                                            @endforeach
                                        </div>

                                        <!-- The slideshow/carousel -->
                                        <div class="carousel-inner rounded">
                                            @foreach ($images as $index => $image)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                        class="d-block w-100 rounded object-fit-cover"
                                                        style="height: 300px;"
                                                        onerror="this.src='{{ asset('assets/media/misc/property-placeholder.jpg') }}'"
                                                        alt="{{ $property->name }}" />
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Left and right controls/icons -->
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    </div>
                                    <!--end::Carousel-->

                                    <!-- Thumbnails -->
                                    <div class="d-flex gap-2 mt-2 overflow-auto py-2">
                                        @foreach ($images as $index => $image)
                                            <img src="{{ asset('storage/' . $image) }}" class="rounded cursor-pointer"
                                                style="height: 60px; width: 60px; object-fit: cover;"
                                                onclick="document.querySelector('#{{ $carouselId }}').querySelector('.carousel-indicators button:nth-child({{ $index + 1 }})').click()"
                                                onerror="this.src='{{ asset('assets/media/misc/property-placeholder.jpg') }}'"
                                                alt="{{ $property->name }}" />
                                        @endforeach
                                    </div>
                                </div>
                                <!--end::Image slider-->

                                <!--begin::Features-->
                                <div class="d-flex flex-wrap gap-5 mt-8">
                                    <div
                                        class="border border-dashed border-gray-300 rounded min-w-125px py-3 px-4 text-center mb-3">
                                        <div class="fs-6 fw-bold text-gray-700">Price per Night</div>
                                        <div class="fs-2 fw-bold text-primary">
                                            ${{ number_format($property->price_per_night, 2) }}</div>
                                    </div>

                                    <div
                                        class="border border-dashed border-gray-300 rounded min-w-125px py-3 px-4 text-center mb-3">
                                        <div class="fs-6 fw-bold text-gray-700">Date of Booking</div>
                                        <div class="fs-6 fw-bold text-success">
                                            {{ Carbon\Carbon::parse($selected_date)->format('Y/m/d') }}</div>
                                    </div>
                                </div>
                                <!--end::Features-->

                                <!--begin::Actions-->
                                <div class="text-center mt-8">
                                    <a href="{{ route('booking-management.bookings.create', ['id' => $property->id]) }}"
                                        class="btn btn-primary btn-hover-scale">
                                        <i class="ki-duotone ki-calendar-add fs-2 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        Book Property
                                    </a>
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center p-5">
                            <i class="ki-duotone ki-information-5 fs-2hx text-info me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-info">No Available Properties</h4>
                                <span>No properties available for booking on the selected date.</span>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize on page load
            Livewire.on('propertiesUpdated', () => {
                setTimeout(() => {
                    // No need to initialize anything
                }, 100);
            });
        </script>
    @endpush
</div>
