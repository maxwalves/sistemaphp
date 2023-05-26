<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EnvioGestorToDiretoria extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $userDiretoria;
    /**
     * Create a new message instance.
     */
    public function __construct(int $userId, int $userDiretoriaId)
    {
        $this->user = User::findOrFail($userId);
        $this->userDiretoria = User::findOrFail($userDiretoriaId);
    }

    /**
     * Get the message envelope.
     */

    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
                    ->subject('Sistema de Controle de Viagens')
                    ->view('emails.envioEmailGestorToDiretoria'); // Substitua 'emails.exemplo' pelo nome da sua visualização de e-mail
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
