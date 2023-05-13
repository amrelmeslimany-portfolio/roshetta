<?php

//**************************************************** TOKEN *****************************************************//

require_once __DIR__.'/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//************************************************************* Function Encode Token ********************************************************//
function TokenEncode($data)
{
    try {
        $expiration_time = time() + (8 * 60 * 60); // Expires in 1 hour
        $key             = "%ExU+d!Sd4AZBHsfyLse77YX)B^eAN(rd!*+FdM6r@f#yGwwJNSA4gqaCbTX2@h4";

        $payload = [
            "id"     => $data->id,
            "type"   => $data->role,
            "exp"    => $expiration_time,
            'iss'    => $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].".roshetta.eg",
            'aud'    => $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].".roshetta.com",
            'iat'    => 1356999524,
            'nbf'    => 1357000000
        ];

        @$token = JWT::encode($payload, $key, 'HS256');
        $data = [
            "token" => $token,
            "exp" => $expiration_time
        ];
        return $data;
    } catch (Exception) {
        return null;
    }
}

//************************************************************* Function Decode Token ********************************************************// 
function TokenDecode($Auth)
{
    try {
        if ($Auth) {
            // Decode Token
            $key = "%ExU+d!Sd4AZBHsfyLse77YX)B^eAN(rd!*+FdM6r@f#yGwwJNSA4gqaCbTX2@h4";
            @$token_decode = JWT::decode($Auth, new Key($key, 'HS256'));
            $decode_array = (array) $token_decode;
            $data = [
                "id" => $decode_array['id'],
                "type" => $decode_array['type']
            ];
            return $data;
        } else {
            return false;
        }
    } catch (Exception) {
        return false;
    }
}
