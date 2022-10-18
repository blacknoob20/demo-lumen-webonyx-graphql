<?php

namespace App\Graphql\Resolvers;

use App\Graphql\Types\UsuarioTypes;
use App\Models\Oracle\SE\Usuario;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UsuarioResolvers
{
    public static function get()
    {
        return new ObjectType([
            'name' => 'UsuarioResolver',
            'description' => 'Funciones de usuario',
            'fields' => [
                'get' => fn () => [
                    'type' => UsuarioTypes::get(),
                    'args' => [
                        'idusuario' => Type::nonNull(Type::string())
                    ],
                    'resolve' => fn ($_, $args) => (new Usuario($args))->getFull(),
                ]
            ],
        ]);
    }
}
