<?php

namespace GetCandy\Plugins\TrackingNotify\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use GetCandy\Api\Orders\Models\Order;

class TrackingMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    
    protected $template;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->template = 'trackingnotify::email';
        if (view()->exists('emails.trackingnotify')) {
            $this->template = 'emails.trackingnotify';
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->template)->subject('Your order has shipped');
    }
}
