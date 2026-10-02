<?php

namespace App\Notifications;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PesananDibatalkan extends Notification
{
    use Queueable;

    public $pesanan;
    public $alasan;
    public $infoRefund;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pesanan $pesanan, $alasan, $infoRefund = null)
    {
        $this->pesanan = $pesanan;
        $this->alasan = $alasan;
        $this->infoRefund = $infoRefund;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pesanan_id' => $this->pesanan->id,
            'nomor_pesanan' => $this->pesanan->nomor_pesanan,
            'title' => 'Pesanan Dibatalkan',
            'message' => "Pesanan #{$this->pesanan->nomor_pesanan} telah dibatalkan.",
            'alasan' => $this->alasan,
            'info_refund' => $this->infoRefund,
            'type' => 'danger' // for UI styling, e.g. red icon
        ];
    }
}
