<?php

//**************************************************** TOKEN *****************************************************//

require_once('../app/helpers/token/vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//************************************************************* Function Encode Token ********************************************************//
function TokenEncode($data)
{
    try {
        $expiration_time = time() + (8 * 60 * 60); // Expires in 8 hour
        $key             = "my_secret_key";

        $payload = [
            "id"     => $data->id,
            "type"   => $data->role,
            "exp"    => $expiration_time
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
            $key = "my_secret_key";
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