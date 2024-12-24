<?php

namespace App\Providers;

use App\Core\KTBootstrap;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\Booking\BookingPriceCalculatorInterface::class,
            \App\Services\Booking\BookingPriceCalculator::class
        );

        $this->app->bind(
            \App\Services\Booking\BookingAvailabilityCheckerInterface::class,
            \App\Services\Booking\BookingAvailabilityChecker::class
        );

        $this->app->bind(
            \App\Services\Booking\BookingCreatorInterface::class,
            \App\Services\Booking\BookingCreator::class
        );

        $this->app->bind(
            \App\Services\Booking\BookingValidatorInterface::class,
            \App\Services\Booking\BookingValidator::class
        );

        $this->app->bind(
            \App\Services\Booking\BookingDateManagerInterface::class,
            \App\Services\Booking\BookingDateManager::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Update defaultStringLength
        Schema::defaultStringLength(191);

        \App\Models\Booking::observe(\App\Observers\BookingObserver::class);

        KTBootstrap::init();
    }
}
