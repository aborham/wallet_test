<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessAddFunds;
use App\Jobs\ProcessTransfer;
use App\Jobs\ProcessWithdraw;
use App\Services\WalletService;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function addFunds(Request $request)
    {
        $userId = $request->input('user_id');
        $amount = $request->input('amount');
        $paymentMethod = $request->input('payment_method');

        $result = $this->walletService->addFunds($userId, $amount, $paymentMethod);

        return response()->json($result);
    }

    public function transfer(Request $request)
    {
        $senderId = $request->input('sender_id');
        $recipientId = $request->input('recipient_id');
        $amount = $request->input('amount');

        $result = $this->walletService->transfer($senderId, $recipientId, $amount);

        return response()->json($result);
    }

    public function withdraw(Request $request)
    {
        $userId = $request->input('user_id');
        $amount = $request->input('amount');
        $bankAccount = $request->input('bank_account');

        $result = $this->walletService->withdraw($userId, $amount, $bankAccount);

        return response()->json($result);
    }

    public function generateUserTransactionsPdf(Request $request)
    {
        $userId = $request->input('user_id');

        $result = $this->walletService->generateUserTransactionsPdf($userId);

        return response()->json($result);
    }
}
