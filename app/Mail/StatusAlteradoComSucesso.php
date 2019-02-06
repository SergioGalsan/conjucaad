<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusAlteradoComSucesso extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $inscricao;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inscricao)
    {
        $this->inscricao = $inscricao;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('contato@conjucaad.com.br')
                    ->view('mail.StatusAlteradoComSucesso')
                    ->subject("O status da sua inscrição foi alterado.")
                    ->with([
                        'inscricao' => $this->inscricao,
                    ]);
    }
}
