<?php

namespace App\Core;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Validator;

class BaseEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $template;
    public $data;
    public $attachmentList;
    protected $copies;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $template, $data, $attachmentList, $copies)
    {
        $this->subject = $subject;
        $this->template = $template;
        $this->data = $data;
        $this->attachmentList = $attachmentList;
        $this->copies = $copies;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view($this->template)
            ->subject($this->subject)
            ->with(['data' => $this->data]);

        if (count($this->attachmentList)) {
            foreach ($this->attachmentList as $key => $value) {
                $path = storage_path(str_replace('/storage', 'app/public', $value->path));
                if (file_exists($path)) {
                    $ext = explode('.', $path)[0];
                    $email->attach($path, [
                        'as' => "$value->file_name.$ext"
                    ]);
                }
            }
        }

        if (count($this->copies)) {
            foreach ($this->copies as $key => $value) {
                $validations = Validator::make(['email' => $value], [
                    'email' => 'required|email',
                ]);
                if (!$validations->fails()) {
                    $email->cc($value);
                }
            }
        }
        return $email;
    }
}
