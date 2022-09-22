<?php
namespace App\Graphql;

use App\Graphql\Resolvers\UsuarioResolvers;
use App\Graphql\Types\UsuarioTypes;
use GraphQL\Type\Definition\ObjectType;

class GraphQLDefinition
{
    public static function query()
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                'usuarios' => [
                    'type' => UsuarioResolvers::get(),
                    'description' => 'Schema de usuarios',
                    'resolve' => fn () => UsuarioTypes::get(),
                ]
            ],
        ]);
    }
}
