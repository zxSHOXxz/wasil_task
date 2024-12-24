<!--begin::Property details-->
<div class="d-flex align-items-center">
    <!--begin::Property images-->
    <div class="me-5">
        @php
            $images = json_decode($property->images, true) ?? [];
        @endphp

        @if (!empty($images))
            <div class="property-thumbnails d-flex gap-2" style="max-width: 200px;">
                @foreach(array_slice($images, 0, 3) as $index => $image)
                    <div class="thumbnail position-relative" style="width: 45px; height: 45px;">
                        <img src="{{ Storage::url($image) }}" 
                             alt="Property Image {{ $index + 1 }}" 
                             class="rounded cursor-pointer"
                             style="width: 45px; height: 45px; object-fit: cover;"
                             data-bs-toggle="modal" 
                             data-bs-target="#propertyImagesModal{{ $property->id }}" />
                    </div>
                @endforeach
                @if(count($images) > 3)
                    <div class="thumbnail position-relative bg-light rounded d-flex align-items-center justify-content-center" 
                         style="width: 45px; height: 45px; cursor: pointer;"
                         data-bs-toggle="modal" 
                         data-bs-target="#propertyImagesModal{{ $property->id }}">
                        <span class="text-primary fw-bold">+{{ count($images) - 3 }}</span>
                    </div>
                @endif
            </div>

            <!-- Images Modal -->
            <div class="modal fade" id="propertyImagesModal{{ $property->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $property->name }} - معرض الصور</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                @foreach($images as $index => $image)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="property-image-container position-relative">
                                            <img src="{{ Storage::url($image) }}" 
                                                 alt="Property Image {{ $index + 1 }}" 
                                                 class="img-fluid rounded w-100"
                                                 style="height: 250px; object-fit: cover;">
                                            <div class="position-absolute bottom-0 start-0 p-2 bg-dark bg-opacity-50 text-white rounded-bottom w-100">
                                                صورة {{ $index + 1 }} من {{ count($images) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="symbol-label bg-light-primary" style="width: 45px; height: 45px;">
                <i class="ki-duotone ki-home fs-2x text-primary">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
        @endif
    </div>
    <!--end::Property images-->

    <!--begin::Property details-->
    <div class="d-flex flex-column">
        <a href="{{ route('property-management.properties.show', $property) }}"
            class="text-gray-800 text-hover-primary mb-1 fs-6 fw-bold">
            {{ $property->name }}
        </a>
        <span class="text-gray-400 fs-7">{{ Str::limit($property->description, 50) }}</span>
    </div>
    <!--end::Property details-->
</div>
<!--end::Property details-->
