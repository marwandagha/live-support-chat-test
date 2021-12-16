<?php

namespace App\Http\Controllers;

use App\Mail\QuestionAnswered;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public static function sendQuestionAnsweredEmail($email, $name, $question, $reply)
    {
        $data = [
            'name' => $name,
            'question' => $question,
            'reply' => $reply
        ];

        try {

            Mail::to($email)->send(new QuestionAnswered($data));

        } catch (\Exception $e) {

        }
    }
}
