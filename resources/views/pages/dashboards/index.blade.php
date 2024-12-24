<x-default-layout>
    @section('title')
        Dashboard
    @endsection

    @push('styles')
        <style>
            /* Property Card Styles */
            .card.hover-elevate-up {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card.hover-elevate-up:hover {
                transform: translateY(-5px);
                box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
            }

            /* Carousel Styles */
            .property-gallery .carousel-inner {
                border-radius: 0.75rem;
                overflow: hidden;
                box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
            }

            .property-gallery .carousel-item {
                transition: transform 0.6s ease-in-out;
            }

            .property-gallery .carousel-item img {
                width: 100%;
                height: 300px;
                object-fit: cover;
            }

            .property-gallery .carousel-caption {
                background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
                left: 0;
                right: 0;
                bottom: 0;
                padding: 2rem 1rem 1rem;
            }

            .property-gallery .carousel-control-prev,
            .property-gallery .carousel-control-next {
                width: 3rem;
                height: 3rem;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 50%;
                top: 50%;
                transform: translateY(-50%);
                margin: 0 1rem;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .property-gallery:hover .carousel-control-prev,
            .property-gallery:hover .carousel-control-next {
                opacity: 1;
            }

            .property-gallery .carousel-control-prev-icon,
            .property-gallery .carousel-control-next-icon {
                width: 1.5rem;
                height: 1.5rem;
                filter: invert(1) grayscale(100);
            }

            /* Thumbnail Styles */
            .property-gallery .d-flex img {
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }

            .property-gallery .d-flex img:hover {
                opacity: 1 !important;
                transform: scale(1.05);
            }

            .property-gallery .d-flex img.border-primary {
                box-shadow: 0 0 0 2px var(--kt-primary) !important;
            }

            /* Button Styles */
            .btn.hover-elevate-up {
                transition: transform 0.3s ease;
            }

            .btn.hover-elevate-up:hover {
                transform: translateY(-2px);
            }
        </style>
    @endpush

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection

    @if (auth()->user()->hasRole('user'))
        <livewire:property.available-properties />
    @endif

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
        <script src="{{ asset('assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/tiny-slider/tiny-slider.bundle.js') }}"></script>
        <script>
            // Initialize Tiny Slider for all property cards
            document.querySelectorAll('[data-tns="true"]').forEach(element => {
                let options = {
                    items: 1,
                    slideBy: 1,
                    autoplay: true,
                    autoplayButtonOutput: false,
                    autoplayTimeout: 3000,
                    speed: 1000,
                    controls: false,
                    nav: true,
                    navPosition: "bottom",
                    touch: true,
                };

                tns(options);
            });
        </script>
    @endpush
</x-default-layout>
