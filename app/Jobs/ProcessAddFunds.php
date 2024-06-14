<?php

namespace App\Jobs;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Jobs\GenerateTransactionPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessAddFunds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $amount;
    protected $paymentMethod;

    public function __construct($userId, $amount, $paymentMethod)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->paymentMethod = $paymentMethod;
    }

    public function handle()
    {
        DB::beginTransaction();
        try {
            $wallet = Wallet::firstOrCreate(['user_id' => $this->userId]);
            $wallet->balance += $this->amount;
            $wallet->save();

            $transaction = Transaction::create([
                'user_id' => $this->userId,
                'type' => 'add_funds',
                'amount' => $this->amount,
                'payment_method' => $this->paymentMethod,
                'status' => 'completed',
            ]);

            // Dispatch the job to generate the PDF
            GenerateTransactionPdf::dispatch($transaction);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception as needed
        }
    }
}
