<?php

namespace App\Graphql\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class SistemaTypes
{
    public static function get()
    {
        return new ObjectType([
            'name' => 'SistemaType',
            'description' => 'Tipo Sistema',
            'fields' => [
                'id'            => Type::int(),
                'nombre'        => Type::string(),
                'numpendientes' => Type::int(),
            ],
        ]);
    }
}