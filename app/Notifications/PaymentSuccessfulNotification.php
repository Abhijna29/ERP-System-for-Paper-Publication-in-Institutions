<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PaymentSuccessfulNotification extends Notification
{
    use Queueable;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add 'mail' if needed
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Payment Successful',
            'message' => 'Payment received from ' . $this->invoice->user->name . ' for Invoice #' . $this->invoice->invoice_number,
            'invoice_id' => $this->invoice->id,
            'link' => route('admin.paymentReport'),

        ];
    }
}
