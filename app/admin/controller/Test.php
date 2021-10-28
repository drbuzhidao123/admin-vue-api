<?php

namespace app\admin\controller;

use app\BaseController;

class Test extends BaseController
{
    public function AES()
    {
        // 要加密的字符串  
        $data = 'test';
        // 密钥  
        $key = 'adminvuekey';
        // 加密数据 'AES-128-ECB' 可以通过openssl_get_cipher_methods()获取  
        $encrypt = openssl_encrypt($data, 'AES-128-ECB', $key, 0);
        $decrypt = openssl_decrypt($encrypt, 'AES-128-ECB', $key, 0);
        var_dump($decrypt);
    }
}
