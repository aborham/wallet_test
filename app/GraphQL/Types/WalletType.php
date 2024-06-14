<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class WalletType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Wallet',
        'description' => 'A wallet type',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the wallet',
            ],
            'user_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The user id of the wallet owner',
            ],
            'balance' => [
                'type' => Type::float(),
                'description' => 'The balance of the wallet',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The creation time of the wallet',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The update time of the wallet',
            ],
        ];
    }
}
