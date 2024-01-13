<?php

use App\Core\BaseEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


if(!function_exists('sendEmail')){

    function sendEmail($recipient, $subject, $template, $data, $attachmentList = [], $copies = [])
    {
        $validations = Validator::make(['email' => $recipient], [
            'email' => 'required|email',
        ]);
        if (!$validations->fails()) {
            Mail::to($recipient)->queue(new BaseEmail($subject, $template, $data, $attachmentList, $copies));
        } else {
           //log para email
        }
    }
} 