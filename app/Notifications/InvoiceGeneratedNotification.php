<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvoiceGeneratedNotification extends Notification
{
    use Queueable;

    protected $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    // Only use 'database'
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'invoice',
            'title' => 'An invoice has been generated for your approved paper',
            'message' => ' . ' . $this->invoice->description,
            'invoice_id' => $this->invoice->id,
            'paper_id' => $this->invoice->paper_id ?? null,
            'link' => route('researcher.invoices', $this->invoice->id),
        ];
    }
}
