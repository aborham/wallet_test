<?php

namespace App\Jobs;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $senderId;
    protected $recipientId;
    protected $amount;

    public function __construct($senderId, $recipientId, $amount)
    {
        $this->senderId = $senderId;
        $this->recipientId = $recipientId;
        $this->amount = $amount;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();

            $senderWallet = Wallet::where('user_id', $this->senderId)->lockForUpdate()->first();
            $recipientWallet = Wallet::where('user_id', $this->recipientId)->lockForUpdate()->first();

            if (!$senderWallet || $senderWallet->balance < $this->amount) {
                throw new \Exception('Insufficient funds');
            }

            if (!$recipientWallet) {
                $recipientWallet = Wallet::create(['user_id' => $this->recipientId, 'balance' => 0]);
            }

            $senderWallet->balance -= $this->amount;
            $senderWallet->save();

            $recipientWallet->balance += $this->amount;
            $recipientWallet->save();

            Transaction::create([
                'user_id' => $this->senderId,
                'type' => 'transfer',
                'amount' => $this->amount,
                'status' => 'completed',
                'recipient_id' => $this->recipientId,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception as needed
        }
    }
}
