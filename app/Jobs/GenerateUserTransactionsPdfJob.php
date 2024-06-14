<?php

namespace App\Jobs;

use App\Models\Transaction;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateUserTransactionsPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Fetch all transactions for the user
        $transactions = Transaction::where('user_id', $this->userId)->get();

        if ($transactions->isEmpty()) {
            return;
        }

        // Data for the PDF
        $data = [
            'title' => 'Transaction History',
            'transactions' => $transactions,
            'date' => date('m/d/Y'),
        ];

        // Generate the PDF
        $pdf = PDF::loadView('transactionHistory', $data);

        // Save the PDF to storage
        $pdfPath = 'pdfs/transaction_history_user_' . $this->userId . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        // Dispatch the event with the PDF URL
        event(new GenerateUserTransactionsPdfCompleted($pdfUrl));
    }
}
