<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use GraphQL;

class UserQuery extends Query
{
    protected $attributes = [
        'name' => 'user',
        'description' => 'A query to find a user by ID or email',
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::string(),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return User::find($args['id']);
        }

        if (isset($args['email'])) {
            return User::where('email', $args['email'])->first();
        }

        return null;
    }
}
