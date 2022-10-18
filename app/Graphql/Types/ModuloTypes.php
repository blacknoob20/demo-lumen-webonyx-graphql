<?php

namespace App\Graphql\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ModuloTypes
{
    public static function get()
    {
        return new ObjectType([
            'name' => 'ModuloType',
            'description' => 'Tipo Modulo',
            'fields' => [
                'id'   => Type::int(),
                'name' => Type::string(),
            ],
        ]);
    }
}