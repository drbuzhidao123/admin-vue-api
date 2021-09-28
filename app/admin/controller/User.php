<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\User as ModelUser;
use think\Request;

class User extends Base
{
    public function getUserList(Request $request)
    {
        $pagenum =  trim($request->param->pagenum);
        $pagesize = trim($request->param->pagesize);
        $query = trim($request->param->query);
        if (empty($pagenum) || empty($pagesize)) {
            return show(config('status.error'), '传输数据为空', null);
        }
        $userObj = new ModelUser();
        $userList = $userObj->getUserList($pagenum, $pagesize, $query);
        $Total = $userObj->getUserTotal($query);
        $res["list"] = $userList;
        $res["total"] = $Total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getAllUserList()
    {
        $userObj = new ModelUser();
        $res = $userObj->select()->toArray();
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getUser()
    {
        $userId =  \trim(request()->param('userId'));
        if (empty($userId)) {
            return \show(config('status.error'), '传输数据为空', null);
        }
        $userObj = new ModelUser();
        $res =$userObj->getUserById($userId);
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function changeStatus()
    {
        $userId = trim(request()->param('userId'));
        $status = trim(request()->param('status'));
        $userObj = new ModelUser();
        $res = $userObj->updateStatusByid($userId, $status); //返回0或1
        if (!$res || empty($res)) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }


    public function addUser(Request $request)
    {
        $user = $request->param;
        $userObj = new ModelUser();
        $user->password = passwordMd5($user->password);
        $res = $userObj->save($user); //返回boolse值
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function edit()
    {
        $user = Request::param();
        $userObj = new ModelUser();
        $res = $userObj->updateById($user['id'], $user);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function remove()
    {
        $userId = Request::param('userId');
        $userObj = new ModelUser();
        $res = $userObj->delete($userId);//单个或批量删除
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }


}
