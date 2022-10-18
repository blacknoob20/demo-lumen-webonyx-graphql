<?php

namespace App\Graphql\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UsuarioTypes
{
    // public static function get()
    // {
    //     return new ObjectType([
    //         'name' => 'UsuarioType',
    //         'description' => 'Tipo Usuario',
    //         'fields' => [
    //             'idusuario'              => Type::string(),
    //             'idpersona'              => Type::int(),
    //             'descripcion'            => Type::string(),
    //             'clave'                  => Type::string(),
    //             'estado'                 => Type::string(),
    //             'fechavigencia'          => Type::string(),
    //             'fecharegistro'          => Type::string(),
    //             'idusuario_log'          => Type::string(),
    //             'ip'                     => Type::string(),
    //             'pcimpresora'            => Type::string(),
    //             'nombreimpresora'        => Type::string(),
    //             'servidor'               => Type::string(),
    //             'puerto'                 => Type::string(),
    //             'numero_intentos'        => Type::int(),
    //             'ultima_sesion'          => Type::string(),
    //             'pregunta'               => Type::string(),
    //             'respuesta'              => Type::string(),
    //             'idusuariored'           => Type::string(),
    //             'fechaaceptaterminosuso' => Type::string(),
    //             'fechaaceptavacaciones'  => Type::string(),
    //             'tiporeemplazo'          => Type::string(),
    //             'idempleadoreemplazo'    => Type::int(),
    //         ],
    //     ]);
    // }

    public static function get()
    {
        return new ObjectType([
            'name' => 'UsuarioFullType',
            'description' => 'Tipo Usuario Full',
            'fields' => [
                'idusuario'              => Type::string(),
                'idpersona'              => Type::int(),
                'nombre'                 => Type::string(),
                'descripcion'            => Type::string(),
                'estado'                 => Type::string(),
                'desestado'              => Type::string(),
                'fecharegistro'          => Type::string(),
                'idusuario_log'          => Type::string(),
                'pcimpresora'            => Type::string(),
                'nombreimpresora'        => Type::string(),
                'servidor'               => Type::string(),
                'puerto'                 => Type::string(),
                'idusuariored'           => Type::string(),
                'idempleado'             => Type::int(),
                'idestructuraorganica'   => Type::int(),
                'esjerarquicosuperior'   => Type::string(),
                'email'                  => Type::string(),
                'emailinterno'           => Type::string(),
                'numdocumento'           => Type::string(),
                'idtipoidentificacion'   => Type::string(),
                'calle'                  => Type::string(),
                'telefono'               => Type::string(),
                'celular'                => Type::string(),
                'emailinterno'           => Type::string(),
                'ultima_sesion'          => Type::string(),
                'idestructurapadre'      => Type::int(),
                'desestructurapadre'     => Type::string(),
                'pregunta_1'             => Type::string(),
                'respuesta_1'            => Type::string(),
                'pregunta_2'             => Type::string(),
                'respuesta_2'            => Type::string(),
                'pregunta_3'             => Type::string(),
                'respuesta_3'            => Type::string(),
                'pregunta_4'             => Type::string(),
                'respuesta_4'            => Type::string(),
                'pregunta_5'             => Type::string(),
                'respuesta_5'            => Type::string(),
                'fechaaceptaterminosuso' => Type::string(),
                'fechaaceptavacaciones'  => Type::string(),
                'fechacalculovacacion'   => Type::string(),
                'idtipoempleado'         => Type::string(),
                'idcargo'                => Type::int(),
                'nombrecargo'            => Type::string(),
                'subalternos'            => Type::string(),
                'notificaciones'         => Type::string(),
                'tiporeemplazo'          => Type::string(),
                'idempleadoreemplazo'    => Type::string(),
                'nombreestructuraruta'   => Type::string(),
                'ultima_sesion'          => Type::string(),
                'idubicacion'            => Type::int(),
                'desidubicacion'         => Type::string(),
                'bloqueotemporal'        => Type::string(),
                'sistemas'               => Type::listOf(SistemaTypes::get()),
                'areas'                  => Type::listOf(AreaTypes::get()),
                'modulos'                => Type::listOf(ModuloTypes::get()),
                'companeros'             => Type::listOf(EmpleadoTypes::get()),

            ],
        ]);
    }
}
