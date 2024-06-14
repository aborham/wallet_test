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

class GenerateTransactionPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function handle()
    {
        $data = [
            'title' => 'Transaction Receipt',
            'transaction' => $this->transaction,
            'date' => date('m/d/Y'),
        ];

        $pdf = PDF::loadView('transactionReceipt', $data);

        $pdfPath = 'pdfs/transaction_receipt_' . $this->transaction->id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $this->transaction->pdf_url = Storage::url($pdfPath);
        $this->transaction->save();
    }
}
