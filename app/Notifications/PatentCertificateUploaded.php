<?php

namespace App\Notifications;

use App\Models\Patents;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PatentCertificateUploaded extends Notification implements ShouldQueue
{
    use Queueable;

    public $patent;

    public function __construct(Patents $patent)
    {
        $this->patent = $patent;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Patent certificate uploaded for: ' . $this->patent->work_title,
            'patent_id' => $this->patent->id,
            'user_name' => $this->patent->investors_name,
            'link' => route('admin.patents.index'),
        ];
    }
}
