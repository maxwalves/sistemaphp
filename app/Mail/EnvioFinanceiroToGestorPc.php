<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EnvioFinanceiroToGestorPc extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $manager;
    public $idAv;
    /**
     * Create a new message instance.
     */
    public function __construct(int $userId, int $managerId, $idAv)
    {
        $this->user = User::findOrFail($userId);
        $this->manager = User::findOrFail($managerId);
        $this->idAv = $idAv;
    }

    /**
     * Get the message envelope.
     */

    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
                    ->subject('Sistema de Controle de Viagens')
                    ->view('emails.envioEmailFinanceiroToGestorPc');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
