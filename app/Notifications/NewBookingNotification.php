<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewBookingNotification extends Notification
{
    // use Queueable;

    protected $booking;
    protected $notifiable;

    public function __construct(Booking $booking, $notifiable)
    {
        $this->booking = $booking;
        $this->notifiable = $notifiable;
    }

    public function via($notifiable): array
    {
        $this->notifiable = $notifiable;
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        $user = $this->booking->user;
        $property = $this->booking->property;

        return (new MailMessage)
            ->subject('حجز جديد في النظام')
            ->line('تم إنشاء حجز جديد في النظام.')
            ->line('تفاصيل الحجز:')
            ->line('المستأجر: ' . $user->name)
            ->line('العقار: ' . $property->title)
            ->line('تاريخ البداية: ' . $this->booking->start_date)
            ->line('تاريخ النهاية: ' . $this->booking->end_date)
            ->line('المبلغ الإجمالي: ' . $this->booking->total_amount)
            ->action('عرض الحجز', url('/bookings/' . $this->booking->id));
    }



    public function toArray($notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'property_id' => $this->booking->property_id,
            'user_id' => $this->booking->user_id,
            'start_date' => $this->booking->start_date,
            'end_date' => $this->booking->end_date,
            'total_amount' => $this->booking->total_amount,
            'user_name' => $this->booking->user->name,
            'property_title' => $this->booking->property->title
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        $user = $this->booking->user;
        $property = $this->booking->property;

        return new BroadcastMessage([
            'type' => 'NewBooking',
            'booking_id' => $this->booking->id,
            'property_id' => $this->booking->property_id,
            'user_id' => $this->booking->user_id,
            'user_name' => $user->name,
            'property_title' => $property->title,
            'start_date' => $this->booking->start_date,
            'end_date' => $this->booking->end_date,
            'total_amount' => $this->booking->total_amount,
            'created_at' => now()->toISOString(),
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('private-notifications.' . $this->notifiable->id);
    }

    public function broadcastAs(): string
    {
        return 'new-booking';
    }
}
