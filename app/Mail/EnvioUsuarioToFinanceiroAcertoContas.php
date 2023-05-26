<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EnvioUsuarioToFinanceiroAcertoContas extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $financeiro;
    /**
     * Create a new message instance.
     */
    public function __construct(int $userId, int $financeiroId)
    {
        $this->user = User::findOrFail($userId);
        $this->financeiro = User::findOrFail($financeiroId);
    }

    /**
     * Get the message envelope.
     */

    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
                    ->subject('Sistema de Controle de Viagens')
                    ->view('emails.envioEmailUsuarioToFinanceiroAcertoContas'); // Substitua 'emails.exemplo' pelo nome da sua visualização de e-mail
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
