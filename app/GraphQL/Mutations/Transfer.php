<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Http\Controllers\WalletController;
use App\Services\WalletService;

final readonly class Transfer
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        return $this->walletService->transfer($args['sender_id'], $args['recipient_id'], $args['amount']);
    }
}
