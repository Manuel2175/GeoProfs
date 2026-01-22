<?php

namespace App\Notifications;

use App\Mail\GebruikerHeeftVerlofAangevraagd;
use App\Models\VerlofAanvraag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerlofAangevraagdNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public VerlofAanvraag $verlofAanvraag
    )
    {
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
    public function toMail($notifiable): GebruikerHeeftVerlofAangevraagd
    {
        return (new GebruikerHeeftVerlofAangevraagd($this->verlofAanvraag))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return [
            'message' => $this->verlofAanvraag->user->name .'
            heeft verlof aangevraagd van' . $this->verlofAanvraag->startdatum . 'tot'.
             $this->verlofAanvraag->einddatum,
            'verlofaanvraag' => $this->verlofAanvraag,
        ];
    }
}
