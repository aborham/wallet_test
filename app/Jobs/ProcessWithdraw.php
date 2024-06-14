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

class ProcessWithdraw implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $amount;
    protected $bankAccount;

    public function __construct($userId, $amount, $bankAccount)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->bankAccount = $bankAccount;
    }

    public function handle()
    {
        DB::beginTransaction();
        try {
            $wallet = Wallet::where('user_id', $this->userId)->lockForUpdate()->first();

            if (!$wallet || $wallet->balance < $this->amount) {
                throw new \Exception('Insufficient funds');
            }

            $wallet->balance -= $this->amount;
            $wallet->save();

            $transaction = Transaction::create([
                'user_id' => $this->userId,
                'type' => 'withdraw',
                'amount' => $this->amount,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'bank_account' => $this->bankAccount,
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
