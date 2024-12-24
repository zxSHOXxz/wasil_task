<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use App\Notifications\BookingStatusNotification;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        User::role('admin')
            ->chunk(100, function ($admins) use ($booking) {
                foreach ($admins as $admin) {
                    $admin->notify(new NewBookingNotification($booking, $admin));
                }
            });
    }

    public function updated(Booking $booking): void
    {
        if ($booking->isDirty('status') && in_array($booking->status, ['approved', 'rejected'])) {
            $booking->user->notify(new BookingStatusNotification($booking));
        }
    }
}
