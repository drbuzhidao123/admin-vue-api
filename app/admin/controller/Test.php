<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\controller\Tool;
use app\common\model\Menu;
use app\common\model\Role;
use app\Request;
use app\common\model\dept;
use think\facade\Db;

class Test extends BaseController
{
    public function maketree()
    {

        $MenuObj = new Menu();
        $tool = new Tool;
        $MenuList = $MenuObj->getMenuList(NULL)->toArray();
        $arr = $tool->tree($MenuList, 0);
        \dump($arr);
        exit();
    }

    public function getRole()
    {
        $userId = 1;
        $roleObj = new Role();
        $role = $roleObj->getRoleByUserId($userId)->toArray();
        \dump($role);
    }

    public function AES()
    {
        // 要加密的字符串  
        $data = 'test';
        // 密钥  
        $key = 'adminvuekey';
        // 加密数据 'AES-128-ECB' 可以通过openssl_get_cipher_methods()获取  
        $encrypt = openssl_encrypt($data, 'AES-128-ECB', $key, 0);
        $decrypt = openssl_decrypt($encrypt, 'AES-128-ECB', $key, 0);
        \dump(($decrypt));
    }

    public function middle(Request $request)
    {
        \dump($request);
    }

    public function corsajax()
    {
        return show(config('status.error'), '没有数据', 1);
    }

    public function tree()
    {
        $deptObj = new Dept();
        $id = 1;
        $query = "dept";
        $deptObj
            ->where('id', 4)
            ->exp('parentId', 'replace(parentId,"5a","1234")')
            ->update();
        //Db::execute("update dept set parentId='".$id."a' where parentId like'".$id."%'");
        //Db::query("UPDATE `dept`  SET `parentId` = `parentId`$id  WHERE  `parentId` LIKE 'thinkphp%'");
    }
}
