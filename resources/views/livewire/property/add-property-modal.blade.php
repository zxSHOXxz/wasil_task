<div class="modal fade" id="kt_modal_add_property" tabindex="-1" aria-hidden="true" wire:ignore>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_property_header">
                <h2 class="fw-bold">Add Property</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross', 'fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_property_form" class="form" wire:submit="submit" enctype="multipart/form-data">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_property_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_property_header"
                        data-kt-scroll-wrappers="#kt_modal_add_property_scroll" data-kt-scroll-offset="300px">

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Property Name</label>
                            <input type="hidden" wire:model="property_id" name="property_id" />
                            <input type="text" wire:model="name" name="name"
                                class="form-control form-control-solid mb-3 mb-lg-0"
                                placeholder="Enter property name" />
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Description</label>
                            <textarea wire:model="description" name="description" rows="4"
                                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Enter property description"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Price per Night</label>
                            <input type="number" wire:model="price_per_night" name="price_per_night" step="0.01"
                                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="0.00" />
                            @error('price_per_night')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Property Images</label>
                            <input type="file" wire:model="images" name="images[]" multiple
                                class="form-control form-control-solid mb-3 mb-lg-0" accept="image/*" />

                            <div class="uploading" wire:loading wire:target="images">
                                <span class="text-muted">Uploading...</span>
                            </div>

                            @error('images.*')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            @if ($images)
                                <div class="mt-2 d-flex gap-2 flex-wrap">
                                    @foreach ($images as $image)
                                        <img src="{{ $image->temporaryUrl() }}" alt="Image Preview"
                                            class="img-thumbnail"
                                            style="width: 150px; height: 150px; object-fit: cover;" />
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Status</label>
                            <select wire:model="status" name="status" class="form-select form-select-solid">
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('propertyAdded', function() {
            toastr.success('Property has been successfully saved!');
        });
    </script>
@endpush
