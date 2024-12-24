<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $status;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->status = $booking->status;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $status = ucfirst($this->status);
        $message = $this->status === 'approved'
            ? 'Your booking has been approved!'
            : 'Your booking has been rejected.';

        return (new MailMessage)
            ->subject("Booking {$status} - Booking #{$this->booking->id}")
            ->greeting("Hello {$notifiable->name}")
            ->line($message)
            ->line("Booking Details:")
            ->line("Start Date: " . $this->booking->start_date->format('Y-m-d'))
            ->line("End Date: " . $this->booking->end_date->format('Y-m-d'))
            ->line("Total Amount: $" . number_format($this->booking->total_amount, 2))
            ->line('Thank you for using our service!');
    }
}
