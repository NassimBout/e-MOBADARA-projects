<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class envoipasswordmodifier extends Mailable
{
    use Queueable, SerializesModels;

    public $donneeClient;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $donneeClient)
    {
        $this->donneeClient=$donneeClient;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ensiassocialapplication@gmail.com')->view('emails.envoipasswordmodifier')->subject('Confirmation soumission');//creation de la vue qui va etre envoyer au client
    }
}
