<?php

namespace App\Libraries\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWebToken extends JWT
{
    private $key;

    public function __construct()
    {
        $this->key = env('APP_KEY');
    }

    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */
    public function getToken($payload)
    {
        return parent::encode($payload, $this->key, 'HS256');
    }

    public function getDecodeToken($jwt)
    {
        return parent::decode($jwt, new Key($this->key, 'HS256'));
    }
}
