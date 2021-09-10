<?php

namespace app\common\controller;

use Firebase\JWT\JWT;

class JwtTool
{
    public function makeJwt($userName)
    {
        $nowtime = time();
        $key = "admin_vue_wei";
        //payload：有效数据
        $payload = array(
            "iss" => "http://www.admin-vue-api.com",//签发者
            "aud" => "http://www.admin-vue-api.com",//jwt面向的用户
            "iat" => $nowtime,//签发时间
            "nbf" => $nowtime+10,//什么时间后才可以使用该jwt
            "exp" => $nowtime+(7*24*60*60),//过期时间
            'data' => [
                'userName' => $userName,
            ]
        );
        $jwt = JWT::encode($payload,$key);
        return $jwt;
    }
}
