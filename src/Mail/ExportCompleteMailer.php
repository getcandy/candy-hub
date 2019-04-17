<?php

namespace GetCandy\Hub\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExportCompleteMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $hash;

    public $subject = 'Your export is ready';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('hub::emails.export');
    }
}
