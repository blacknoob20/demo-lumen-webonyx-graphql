<?php

namespace App\Libraries\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWebToken extends JWT
{

    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */
    public static function getToken($payload)
    {
        $key = env('APP_KEY');
        $unixTimeTokenCreation = strtotime('now');
        // TODO: Parametrizar el tiempo de exipración puede ser DB o ENV
        $unixTimeTokenExpiration = strtotime('+1 minutes', $unixTimeTokenCreation);

        return parent::encode(
            [
                ...$payload,
                'iat' => $unixTimeTokenCreation,
                'exp' => $unixTimeTokenExpiration,
            ],
            $key,
            'HS256'
        );
    }

    public static function getDecodedToken($jwt)
    {
        $key = env('APP_KEY');
        return parent::decode($jwt, new Key($key, 'HS256'));
    }
}
