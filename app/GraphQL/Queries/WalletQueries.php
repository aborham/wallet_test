<?php

namespace App\GraphQL\Queries;

use App\Models\Transaction;
use PDF; // For PDF generation
use SimpleSoftwareIO\QrCode\Facades\QrCode; // For QR code generation

class WalletQueries
{
    public function transactions($_, array $args)
    {
        $userId = $args['user_id'];
        // Logic to get transaction history
        $transactions = Transaction::where('user_id', $userId)->get();
        return $transactions;
    }

    public function generatePdf($_, array $args)
    {
        $userId = $args['user_id'];
        // Logic to generate PDF of transactions
        $transactions = Transaction::where('user_id', $userId)->get();
        $pdf = PDF::loadView('transactions_pdf', ['transactions' => $transactions]);
        $pdfUrl = $pdf->save(public_path('pdf/transactions_' . $userId . '.pdf'))->url();

        return ['pdf_url' => $pdfUrl];
    }

    public function generateQrCode($_, array $args)
    {
        $userId = $args['user_id'];
        $recipientId = $args['recipient_id'];
        $amount = $args['amount'];
        // Logic to generate QR code
        $qrCodeUrl = QrCode::format('png')->size(200)->generate(route('transfer', ['user_id' => $userId, 'recipient_id' => $recipientId, 'amount' => $amount]));

        return ['qr_code_url' => $qrCodeUrl];
    }
}
