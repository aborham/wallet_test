<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class WalletResponseType extends GraphQLType
{
    protected $attributes = [
        'name' => 'WalletResponse',
        'description' => 'A wallet response type',
    ];

    public function fields(): array
    {
        return [
            'success' => [
                'type' => Type::boolean(),
                'description' => 'Whether the operation was successful',
            ],
            'message' => [
                'type' => Type::string(),
                'description' => 'A message about the result',
            ],
            'new_balance' => [
                'type' => Type::float(),
                'description' => 'The new balance after the operation',
            ],
        ];
    }
}
