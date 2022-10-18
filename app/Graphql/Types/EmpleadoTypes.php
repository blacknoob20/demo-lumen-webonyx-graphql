<?php

namespace App\Graphql\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class EmpleadoTypes
{
    public static function get()
    {
        return new ObjectType([
            'name' => 'EmpleadoType',
            'description' => 'Tipo Empleado',
            'fields' => [
                'idempleado' => Type::int(),
                'numdocumento' => Type::string(),
                'nombre1' => Type::string(),
                'nombre2' => Type::string(),
                'apellido1' => Type::string(),
                'apellido2' => Type::string(),
                'cargo' => Type::string(),
                'tipoempleado' => Type::string(),
            ],
        ]);
    }
}