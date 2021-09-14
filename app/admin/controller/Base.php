<?php
namespace app\admin\controller;
use app\BaseController;
use app\common\controller\JwtTool;
use Firebase\JWT\JWT;

class Base extends BaseController
{

    public function initialize()
    {
        $token = \request()->header('Authorization');
        $this->checkToken($token);  
    }

    //用于检验 token 是否存在, 并且更新 
    public function checkToken($token)
    {
        $jwtObj = new JwtTool();
        $json_res=[];
        if(empty($token)){
            $json_res = [
                'code' => config("status.token_out"),
                'data' => null,
                'msg' => '没有token,验证失败',
            ];
            json($json_res)->send();exit;//没有token,验证失败
        }

        JWT::$leeway = 60;
        $decoded = JWT::decode($token, $jwtObj->key, array('HS256'));
        $arr = (array)$decoded;
        if ($arr['exp'] < time()) {
            $json_res = [
                'code' => config("status.token_out"),
                'data' => null,
                'message' => 'token超时，请重新登录',
            ];
            json($json_res)->send();exit;
        }

    }
  
}
