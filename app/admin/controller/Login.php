<?php
namespace app\admin\controller;
use app\BaseController;
use app\common\model\user;
use app\common\controller\JwtTool;

class Login extends BaseController
{
    public function check()
    {
        $userName = request()->param('userName');
        $password = request()->param('password');
        $jwtTool = new JwtTool();
        $jwt = $jwtTool->makeJwt($userName);
        \var_dump($jwt);exit();
        if(empty($userName)||empty($password)){
            return show(config('status.error'),'用户名或密码为空',null);
        }
        
        $userObj = new user();
        $user=$userObj->getUserByuserName($userName);
        if(empty($user)){
            return show(config('status.error'),'没有该用户',null);
        }

        $user=$user->toArray();
        if($user['status']!==1){
            return show(config('status.error'),'用户状态为0',null);
        }

        //判断密码是否正确
        if($user['password']!==passwordMd5($password)){
            return show(config('status.error'),'密码错误！',null);
        }

        //正确之后用jwt签出token保存状态
        $token = makeToken();
        if($res){
            return show(config('status.success'),'登录成功！',$res);
        }else{
            return show(config('status.error'),'登录失败！token更新出错！',null);
        }
    }


}
