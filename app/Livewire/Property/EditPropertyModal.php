<?php

namespace App\Livewire\Property;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
use App\Interfaces\PropertyManageable;
use App\Traits\PropertyImageHandler;
use Illuminate\Support\Facades\Storage;

/**
 * EditPropertyModal Component
 * 
 * This component handles the editing of existing properties including their images.
 * It implements the PropertyManageable interface and uses PropertyImageHandler trait
 * for consistent property management across the application.
 */
class EditPropertyModal extends Component implements PropertyManageable
{
    use WithFileUploads, PropertyImageHandler;

    /** @var int|null */
    public $property_id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var float */
    public $price_per_night;

    /** @var string */
    public $status = 'available';

    /** @var array */
    public $images = [];

    /** @var array */
    public $existing_images = [];

    /**
     * Validation rules for property data
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price_per_night' => 'required|numeric|min:0',
        'status' => 'required|in:available,unavailable',
        'images.*' => 'image|max:2048',
    ];

    /**
     * Event listeners for the component
     *
     * @var array
     */
    protected $listeners = [
        'update_property' => 'loadProperty',
    ];

    /**
     * Load property data for editing
     *
     * @param int $propertyId
     * @return void
     */
    public function loadProperty(int $propertyId): void
    {
        $property = Property::findOrFail($propertyId);
        $this->property_id = $property->id;
        $this->name = $property->name;
        $this->description = $property->description;
        $this->price_per_night = $property->price_per_night;
        $this->status = $property->status;
        $this->existing_images = json_decode($property->images) ?? [];
    }

    /**
     * Save property data
     *
     * @return void
     */
    public function saveProperty(): void
    {
        $this->validate();

        $imagesPaths = $this->existing_images;
        if ($this->images) {
            $imagesPaths = array_merge($imagesPaths, $this->storeImages($this->images));
        }

        $property = Property::findOrFail($this->property_id);
        $property->update([
            'name' => $this->name,
            'description' => $this->description,
            'price_per_night' => $this->price_per_night,
            'status' => $this->status,
            'images' => json_encode($imagesPaths)
        ]);

        $this->dispatchEvents();
    }

    /**
     * Submit form handler
     *
     * @return void
     */
    public function submit(): void
    {
        $this->saveProperty();
    }

    /**
     * Remove specific image from property
     *
     * @param string|int $index
     * @return void
     */
    public function removeImage($index): void
    {
        $index = (int) $index;
        if (isset($this->existing_images[$index])) {
            $imagePath = $this->existing_images[$index];
            
            if ($this->removeImageFromStorage($imagePath)) {
                unset($this->existing_images[$index]);
                $this->existing_images = array_values($this->existing_images);

                Property::findOrFail($this->property_id)->update([
                    'images' => json_encode($this->existing_images)
                ]);
            }
        }
    }

    /**
     * Remove image from storage
     *
     * @param string $imagePath
     * @return bool
     */
    private function removeImageFromStorage(string $imagePath): bool
    {
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->delete($imagePath);
        }
        return false;
    }

    /**
     * Dispatch success events
     *
     * @return void
     */
    private function dispatchEvents(): void
    {
        $this->dispatch('success', 'تم تحديث العقار بنجاح');
        $this->dispatch('hideModal');
        $this->dispatch('propertyUpdated');
        $this->reset(['images']);
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.property.edit-property-modal', [
            'existing_images' => $this->existing_images
        ]);
    }
}
