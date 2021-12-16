<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuestionAnswered extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     private $email_data;

    public function __construct($data)
    {
        $this->email_data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
               return $this->from(env('MAIL_FROM_ADDRESS'),'Test')->subject('Question Answered!')->view('mail.contact-mail',['email_data'=> $this->email_data]);

    }
}
