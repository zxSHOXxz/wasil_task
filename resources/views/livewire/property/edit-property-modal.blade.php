<div class="modal fade" id="kt_modal_edit_property" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <!-- Modern clean header -->
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);">
                <h2 class="fw-bolder text-white mb-0 fs-1 d-flex align-items-center">
                    <i class="fas fa-edit me-3 fa-fade"></i>
                    Edit Property Details
                </h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1 text-white"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body p-10" style="background: #F8FAFC;">
                <form id="kt_modal_edit_property_form" class="form" wire:submit.prevent="submit" enctype="multipart/form-data">
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_edit_property_scroll">
                        <!-- Property Details Section -->
                        <div class="card shadow-sm mb-8 hover-elevate-up border-0">
                            <div class="card-header py-5" style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);">
                                <h3 class="card-title fw-bolder text-white fs-2 d-flex align-items-center">
                                    <i class="fas fa-info-circle me-3 fa-beat-fade"></i>
                                    Basic Information
                                </h3>
                            </div>
                            <div class="card-body p-8 bg-white">
                                <div class="row g-9 mb-8">
                                    <div class="col-12">
                                        <label class="required fw-bolder fs-6 mb-3 text-gray-700">Property Name</label>
                                        <input type="hidden" wire:model="property_id" name="property_id" />
                                        <input type="text" wire:model="name" name="name"
                                            class="form-control form-control-lg bg-light-primary hover-elevate-up"
                                            style="border: 1px solid #E2E8F0; border-radius: 0.75rem; transition: all 0.3s ease;"
                                            placeholder="Enter a distinctive name for your property" />
                                        @error('name')
                                            <div class="invalid-feedback d-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-9">
                                    <div class="col-md-8">
                                        <label class="required fw-bolder fs-6 mb-3 text-gray-700">Description</label>
                                        <textarea wire:model="description" name="description" rows="4"
                                            class="form-control form-control-lg bg-light-primary hover-elevate-up"
                                            style="border: 1px solid #E2E8F0; border-radius: 0.75rem; transition: all 0.3s ease;"
                                            placeholder="Provide a detailed description of your property"></textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-8">
                                            <label class="required fw-bolder fs-6 mb-3 text-gray-700">Price per Night</label>
                                            <div class="input-group input-group-lg hover-elevate-up"
                                                 style="border: 1px solid #E2E8F0; border-radius: 0.75rem; overflow: hidden;">
                                                <span class="input-group-text" style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%); color: white; border: none;">$</span>
                                                <input type="number" wire:model="price_per_night" name="price_per_night"
                                                    class="form-control form-control-lg bg-light-primary"
                                                    style="border: none;"
                                                    placeholder="0.00" />
                                            </div>
                                            @error('price_per_night')
                                                <div class="invalid-feedback d-block text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="required fw-bolder fs-6 mb-3 text-gray-700">Status</label>
                                            <select wire:model="status" name="status"
                                                class="form-select form-select-lg bg-light-primary hover-elevate-up"
                                                style="border: 1px solid #E2E8F0; border-radius: 0.75rem; transition: all 0.3s ease;"
                                                data-control="select2" data-placeholder="Select Status">
                                                <option value="available">Available</option>
                                                <option value="unavailable">Unavailable</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback d-block text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Images Section -->
                        <div class="card shadow-sm hover-elevate-up border-0">
                            <div class="card-header py-5" style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);">
                                <h3 class="card-title fw-bolder text-white fs-2 d-flex align-items-center">
                                    <i class="fas fa-images me-3 fa-beat-fade"></i>
                                    Property Images
                                </h3>
                            </div>
                            <div class="card-body p-8 bg-white">
                                <div wire:loading wire:target="editProperty" class="mb-3">
                                    <div class="d-flex align-items-center bg-light-info rounded p-5">
                                        <div class="spinner-border text-primary me-3" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="text-gray-700">Loading property images...</span>
                                    </div>
                                </div>

                                <div wire:loading.remove wire:target="editProperty">
                                    <div class="mb-8">
                                        <label class="fw-bolder fs-6 mb-4 d-block text-gray-700">Current Images</label>
                                        <div class="d-flex flex-wrap gap-5">
                                            @if (is_array($existing_images) && count($existing_images) > 0)
                                                @foreach ($existing_images as $index => $image)
                                                    <div class="position-relative hover-elevate-up">
                                                        <img src="{{ Storage::url($image) }}" alt="Property Image"
                                                            class="rounded-3 shadow-sm"
                                                            style="width: 150px; height: 150px; object-fit: cover; transition: all 0.3s ease;" />
                                                        <button type="button"
                                                            class="btn btn-icon btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle"
                                                            style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none;"
                                                            wire:click="removeImage({{ $index }})">
                                                            <i class="ki-duotone ki-cross fs-2 text-white">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <label class="fw-bolder fs-6 mb-4 text-gray-700">Add New Images</label>
                                        <div class="dropzone bg-light rounded-3 p-8 text-center hover-elevate-up"
                                            style="border: 2px dashed #3B82F6; transition: all 0.3s ease;"
                                            wire:ignore
                                            x-data="{ uploading: false, dragover: false }"
                                            x-on:livewire-upload-start="uploading = true"
                                            x-on:livewire-upload-finish="uploading = false"
                                            x-on:livewire-upload-error="uploading = false"
                                            x-on:dragover="dragover = true"
                                            x-on:dragleave="dragover = false"
                                            x-on:drop="dragover = false"
                                            :class="{ 'bg-light-primary border-primary': dragover }">

                                            <input type="file" wire:model="images" multiple class="d-none" id="property_images" accept="image/*">
                                            <label for="property_images" class="mb-0 cursor-pointer">
                                                <i class="ki-duotone ki-cloud-upload fs-3x text-primary mb-4 fa-bounce"></i>
                                                <p class="fs-5 text-gray-600 mb-0">Drag and drop images here or click to select</p>
                                                <p class="fs-7 text-gray-500 mt-2 mb-0">PNG, JPG, JPEG - Maximum 2MB per image</p>
                                            </label>

                                            <div x-show="uploading" class="mt-4">
                                                <div class="progress h-6px w-100 bg-light-primary">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"
                                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('images.*')
                                            <div class="invalid-feedback d-block text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    @if ($images)
                                        <div class="mb-3">
                                            <h4 class="fw-bolder text-gray-800 mb-3">New Images Preview</h4>
                                            <div class="d-flex flex-wrap gap-5">
                                                @foreach ($images as $image)
                                                    <div class="position-relative">
                                                        <img src="{{ $image->temporaryUrl() }}"
                                                            class="rounded border shadow-sm"
                                                            style="width: 150px; height: 150px; object-fit: cover;" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light btn-lg px-6 me-3 hover-elevate-up"
                            style="min-width: 150px; transition: all 0.3s ease;"
                            data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross-square fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-lg px-6 hover-elevate-up"
                            style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%); border: none; color: white; min-width: 150px; transition: all 0.3s ease;">
                            <i class="ki-duotone ki-check-square fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .hover-elevate-up:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3B82F6 !important;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }
        .modal-content {
            border: none;
        }
        .btn-light {
            background-color: #F1F5F9 !important;
            border-color: #E2E8F0 !important;
        }
        .btn-light-primary {
            background-color: #DBEAFE !important;
            color: #3B82F6 !important;
        }
        .btn-light-primary:hover {
            background-color: #BFDBFE !important;
        }
        .text-primary {
            color: #3B82F6 !important;
        }
        .bg-light-primary {
            background-color: #EFF6FF !important;
        }
        .progress-bar {
            background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%) !important;
        }
        
        /* إضافة تأثيرات جديدة */
        .form-control, .form-select {
            background-color: #F8FAFC !important;
            border: 1px solid #E2E8F0 !important;
            transition: all 0.3s ease;
        }
        
        .form-control:hover, .form-select:hover {
            background-color: #F1F5F9 !important;
            border-color: #3B82F6 !important;
        }
        
        .dropzone {
            background-color: #F8FAFC !important;
            border: 2px dashed #3B82F6 !important;
        }
        
        .dropzone:hover {
            background-color: #EFF6FF !important;
        }
    </style>

    @push('scripts')
        <script>
            var editPropertyModal;

            document.addEventListener('livewire:initialized', () => {
                editPropertyModal = new bootstrap.Modal(document.getElementById('kt_modal_edit_property'));

                @this.on('hideModal', () => {
                    editPropertyModal.hide();
                });;

                @this.on('propertyUpdated', () => {
                    Livewire.dispatch('refreshProperties');
                });
            });

            Livewire.on('show-edit-property-modal', () => {
                editPropertyModal.show();
            });
        </script>
    @endpush
