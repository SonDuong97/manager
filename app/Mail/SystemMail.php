<?php

namespace App\Mail;

use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SystemMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var MailTemplate
     */
    public $template;

    /**
     * @var string
     */
    public $to;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($action)
    {
        $this->template = MailTemplate::where(MailTemplate::COL_ACTION, $action)
                                    ->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from($this->template->sender);
        $this->subject($this->template->title);
        return $this->view('mails.system_mail');
    }
}
