<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Transaction;

final readonly class Transactions
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $userId = $args['user_id'];
        // Logic to get transaction history
        $transactions = Transaction::where('user_id', $userId)->get();
        return $transactions;
    }
}
