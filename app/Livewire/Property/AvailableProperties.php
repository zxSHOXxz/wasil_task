<?php

namespace App\Livewire\Property;

use App\Models\Property;
use Livewire\Component;
use Carbon\Carbon;

/**
 * AvailableProperties Component
 * 
 * This component handles the display and filtering of available properties.
 * It follows the Single Responsibility Principle by focusing solely on
 * managing the availability status of properties.
 */
class AvailableProperties extends Component
{
    /** @var string */
    public $search = '';

    /** @var string */
    public $selected_date;

    /** @var array */
    public $properties = [];

    /**
     * Initialize component state
     *
     * @return void
     */
    public function mount(): void
    {
        $this->selected_date = Carbon::today()->format('Y-m-d');
        $this->loadProperties();
    }

    /**
     * Handle date selection updates
     *
     * @param string $value
     * @return void
     */
    public function updatedSelectedDate(string $value): void
    {
        $this->loadProperties();
    }

    /**
     * Handle search updates
     *
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->loadProperties();
    }

    /**
     * Load available properties based on current filters
     *
     * @return void
     */
    public function loadProperties(): void
    {
        $this->properties = $this->getAvailableProperties();
    }

    /**
     * Get available properties based on current filters
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAvailableProperties()
    {
        return Property::where('status', 'available')
            ->whereDoesntHave('bookings', function ($query) {
                $query->where('status', 'approved')
                    ->where(function ($q) {
                        $selectedDate = Carbon::parse($this->selected_date);
                        $q->where('start_date', '<=', $selectedDate)
                            ->where('end_date', '>', $selectedDate);
                    });
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.property.available-properties');
    }
}
