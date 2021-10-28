<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\model\User;
use app\common\controller\JwtTool;
use think\Request;
use ric\captcha\facade\CaptchaApi;

class Login extends BaseController
{
    public function check(Request $request)
    {
        $param = (array)($request->param);
        if (!CaptchaApi::check($param['captcha'],$param['key'])) {
            return show(config('status.captcha_out'), '验证失败！验证码有误！', null);
        }
        $userName = trim($param['userName']);
        $password = trim($param['password']);
        if (empty($userName) || empty($password)) {
            return show(config('status.error'), '用户名或密码为空', null);
        }

        $userObj = new user();
        $user = $userObj->getUserByuserName($userName);
        if (empty($user)) {
            return show(config('status.error'), '没有该用户', null);
        }

        $user = $user->toArray();
        if ($user['status'] !== 1) {
            return show(config('status.error'), '用户状态为0', null);
        }

        //判断密码是否正确
        if ($user['password'] !== passwordMd5($password)) {
            return show(config('status.error'), '密码错误！', null);
        }

        //正确之后用jwt签出token保存状态
        $jwtTool = new JwtTool();
        $token = $jwtTool->makeJwt($userName, $jwtTool->key);
        if ($token) {
            $user['token'] = $token;
            return show(config('status.success'), '登录成功！', $user);
        } else {
            return show(config('status.error'), '登录失败！token更新出错！', null);
        }
    }
}
