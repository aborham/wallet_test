<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TransactionPDFResponseType extends GraphQLType
{
    protected $attributes = [
        'name' => 'TransactionPDFResponse',
        'description' => 'A transaction response type for pdf url',
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
            'pdf_url' => [
                'type' => Type::string(),
                'description' => 'The history pdf url',
            ],
        ];
    }
}
