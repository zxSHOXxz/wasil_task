<?php

namespace App\Interfaces;

interface PropertyManageable
{
    /**
     * Load property data
     *
     * @param int $propertyId
     * @return void
     */
    public function loadProperty(int $propertyId): void;

    /**
     * Save property data
     *
     * @return void
     */
    public function saveProperty(): void;
}
