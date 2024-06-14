<?php

namespace App\Listeners;

use App\Events\GenerateUserTransactionsPdfCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateUserTransactionsPdfListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GenerateUserTransactionsPdfCompleted  $event
     * @return void
     */
    public function handle(GenerateUserTransactionsPdfCompleted $event)
    {
        // You can store the PDF URL or perform other actions here
        // Example: Store the URL in a database or send a notification to the user
        // For simplicity, we will just log the URL
        \Log::info('PDF generated: ' . $event->pdfUrl);
    }
}
