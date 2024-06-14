<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Transaction;
use PDF; // For PDF generation

final readonly class GeneratePdf
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $userId = $args['user_id'];
        // Logic to generate PDF of transactions
        $transactions = Transaction::where('user_id', $userId)->get();
        $pdf = PDF::loadView('transactions_pdf', ['transactions' => $transactions]);
        $pdfUrl = $pdf->save(public_path('pdf/transactions_' . $userId . '.pdf'))->url();

        return ['pdf_url' => $pdfUrl];
    }
}
