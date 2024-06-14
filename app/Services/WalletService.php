<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Jobs\GenerateUserTransactionsPdfJob;
use App\Jobs\ProcessAddFunds;
use App\Jobs\ProcessTransfer;
use App\Jobs\ProcessWithdraw;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function addFunds($userId, $amount, $paymentMethod)
    {
        // Business logic for adding funds
        $wallet = Wallet::firstOrCreate(['user_id' => $userId]);
        $wallet->balance += $amount;
        $wallet->save();

        $transaction = Transaction::create([
            'user_id' => $userId,
            'type' => 'add_funds',
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'status' => 'completed',
        ]);

        // Dispatch the job to generate the PDF asynchronously
        GenerateUserTransactionsPdfJob::dispatch($userId);

        return [
            'success' => true,
            'message' => 'Funds added successfully.',
            'new_balance' => $wallet->balance,
        ];
    }

    public function transfer($senderId, $recipientId, $amount)
    {
       
        ProcessTransfer::dispatch($senderId, $recipientId, $amount);

        return response()->json([
            'success' => true,
            'message' => 'Transfer will be processed shortly',
        ]);
    }

    public function withdraw( $userId, $amount, $bankAccount)
    {
      DB::beginTransaction();
        try {
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

            if (!$wallet || $wallet->balance < $amount) {
                throw new \Exception('Insufficient funds');
            }

            $wallet->balance -= $amount;
            $wallet->save();

            $transaction = Transaction::create([
                'user_id' => $userId,
                'type' => 'withdraw',
                'amount' => $amount,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'bank_account' => $bankAccount,
            ]);

            // Dispatch the job to generate the PDF
            GenerateTransactionPdf::dispatch($transaction);

            DB::commit();
            return response()->json([
              'success' => true,
              'message' => 'Withdrawal will be processed shortly',
              "new_balance" => 100
          ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception as needed
        }
        //ProcessWithdraw::dispatch($userId, $amount, $bankAccount);
        
    }

    public function generateUserTransactionsPdf( $userId)
    {
        // Dispatch the job to generate the PDF asynchronously
        GenerateUserTransactionsPdfJob::dispatch($userId);

        return response()->json([
            'success' => true,
            'message' => 'PDF generation has been initiated.',
            'pdf_url' => "well be genrated soon"
        ]);
    }
}
