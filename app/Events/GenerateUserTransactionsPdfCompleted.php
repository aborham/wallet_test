<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenerateUserTransactionsPdfCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pdfUrl;

    /**
     * Create a new event instance.
     *
     * @param string $pdfUrl
     * @return void
     */
    public function __construct($pdfUrl)
    {
        $this->pdfUrl = $pdfUrl;
    }
}
