<?php

namespace App\Graphql\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AreaTypes
{
    public static function get()
    {
        return new ObjectType([
            'name' => 'AreaType',
            'description' => 'Tipo Area',
            'fields' => [
                'id'   => Type::int(),
            ],
        ]);
    }
}