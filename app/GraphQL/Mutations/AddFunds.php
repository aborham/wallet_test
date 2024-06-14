<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Services\WalletService;


final readonly class AddFunds
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        return $this->walletService->addFunds($args['user_id'], $args['amount'], $args['payment_method']);
    }
}
