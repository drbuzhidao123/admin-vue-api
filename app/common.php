<?php
// 应用公共文件

function show($code, $msg = 'error', $data = [], $httpStatus = 200)
{
      $result = [
            'code' => $code,
            'data' => $data,
            'msg' => $msg,
      ];
      return json($result, $httpStatus);
}

function passwordMd5($password)
{
      $password = md5($password . 'admin_wei');
      return $password;
}

function makeStrRand()
{
      $str = md5(uniqid(md5(microtime(true)), true)); //生成一个不会重复的字符串
      $str = sha1($str); //加密
      return $str;
}

function time_rand()
{
      $str = md5(time() . mt_rand(1, 100)); //md5加密，time()当前时间戳
      return $str;
}

function get_extension($file) //获取后缀
{
      return substr($file, strrpos($file, '.') + 1);
}
