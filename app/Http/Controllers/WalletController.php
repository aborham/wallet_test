<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function addFunds($userId, $amount, $paymentMethod)
    {
        // Logic to add funds
        $wallet = Wallet::firstOrCreate(['user_id' => $userId]);
        $wallet->balance += $amount;
        $wallet->save();

        // Create a transaction record
        Transaction::create([
            'user_id' => $userId,
            'type' => 'add_funds',
            'amount' => $amount,
            'status' => 'completed',
        ]);

        return [
            'success' => true,
            'message' => 'Funds added',
            'new_balance' => $wallet->balance
        ];
    }

    public function transfer($senderId, $recipientId, $amount)
    {
        // Logic to transfer funds
        DB::transaction(function () use ($senderId, $recipientId, $amount) {
            $senderWallet = Wallet::where('user_id', $senderId)->first();
            $recipientWallet = Wallet::firstOrCreate(['user_id' => $recipientId]);

            if ($senderWallet->balance < $amount) {
                throw new \Exception('Insufficient funds');
            }

            $senderWallet->balance -= $amount;
            $recipientWallet->balance += $amount;

            $senderWallet->save();
            $recipientWallet->save();

            // Create transaction records
            Transaction::create([
                'user_id' => $senderId,
                'type' => 'transfer_out',
                'amount' => $amount,
                'status' => 'completed',
            ]);

            Transaction::create([
                'user_id' => $recipientId,
                'type' => 'transfer_in',
                'amount' => $amount,
                'status' => 'completed',
            ]);
        });

        return [
            'success' => true,
            'message' => 'Funds transferred',
            'new_balance' => Wallet::where('user_id', $senderId)->first()->balance
        ];
    }

    public function withdraw($userId, $amount, $bankAccount)
    {
        // Logic to withdraw funds
        $wallet = Wallet::where('user_id', $userId)->first();

        if ($wallet->balance < $amount) {
            throw new \Exception('Insufficient funds');
        }

        $wallet->balance -= $amount;
        $wallet->save();

        // Create a transaction record
        Transaction::create([
            'user_id' => $userId,
            'type' => 'withdraw',
            'amount' => $amount,
            'status' => 'completed',
        ]);

        return [
            'success' => true,
            'message' => 'Funds withdrawn',
            'new_balance' => $wallet->balance
        ];
    }
}
