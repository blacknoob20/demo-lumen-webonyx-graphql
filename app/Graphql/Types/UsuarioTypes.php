<?php

namespace App\Graphql\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UsuarioTypes
{
    public static function get()
    {
        return new ObjectType([
            'name' => 'UsuarioType',
            'description' => 'Tipo Usuario',
            'fields' => [
                'idusuario'              => Type::string(),
                'idpersona'              => Type::int(),
                'descripcion'            => Type::string(),
                'clave'                  => Type::string(),
                'estado'                 => Type::string(),
                'fechavigencia'          => Type::string(),
                'fecharegistro'          => Type::string(),
                'idusuario_log'          => Type::string(),
                'ip'                     => Type::string(),
                'pcimpresora'            => Type::string(),
                'nombreimpresora'        => Type::string(),
                'servidor'               => Type::string(),
                'puerto'                 => Type::string(),
                'numero_intentos'        => Type::int(),
                'ultima_sesion'          => Type::string(),
                'pregunta'               => Type::string(),
                'respuesta'              => Type::string(),
                'idusuariored'           => Type::string(),
                'fechaaceptaterminosuso' => Type::string(),
                'fechaaceptavacaciones'  => Type::string(),
                'tiporeemplazo'          => Type::string(),
                'idempleadoreemplazo'    => Type::int(),
            ],
        ]);
    }
}
