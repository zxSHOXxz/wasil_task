<?php

namespace App\Livewire\Property;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
use App\Interfaces\PropertyManageable;
use App\Traits\PropertyImageHandler;

/**
 * AddPropertyModal Component
 * 
 * This component handles the creation of new properties.
 * It implements the PropertyManageable interface and uses PropertyImageHandler trait
 * for consistent property management across the application.
 */
class AddPropertyModal extends Component implements PropertyManageable
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
        'delete_property' => 'deleteProperty',
    ];

    /**
     * Delete a property
     *
     * @param int $propertyId
     * @return void
     */
    public function deleteProperty(int $propertyId): void
    {
        $property = Property::findOrFail($propertyId);
        
        // Clean up images before deleting
        if ($property->images) {
            $images = json_decode($property->images, true) ?? [];
            foreach ($images as $image) {
                $this->removeImage($image);
            }
        }
        
        $property->delete();
        $this->dispatch('success', __('Property deleted'));
    }

    /**
     * Load property data
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
    }

    /**
     * Save property data
     *
     * @return void
     */
    public function saveProperty(): void
    {
        $this->validate();

        $imagesPaths = [];
        if ($this->images) {
            $imagesPaths = $this->storeImages($this->images);
        }

        Property::updateOrCreate(
            ['id' => $this->property_id],
            [
                'name' => $this->name,
                'description' => $this->description,
                'price_per_night' => $this->price_per_night,
                'status' => $this->status,
                'images' => json_encode($imagesPaths)
            ]
        );

        $this->dispatch('success', __('New Property created'));
        $this->reset();
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
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.property.add-property-modal');
    }
}
