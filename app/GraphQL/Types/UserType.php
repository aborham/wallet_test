<?php

namespace  App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A user type',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the user',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the user',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of the user',
            ],
            'email_verified_at' => [
                'type' => Type::string(),
                'description' => 'The email verification time of the user',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The creation time of the user',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The update time of the user',
            ],
            'wallet' => [
                'type' => \GraphQL::type('Wallet'),
                'description' => 'The wallet of the user',
            ],
        ];
    }
}
