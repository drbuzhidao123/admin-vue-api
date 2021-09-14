<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\User as ModelUser;
use think\facade\Request;

class User extends Base
{
    public function getUserList()
    {
        $pagenum =  \trim(request()->param('pagenum'));
        $pagesize = \trim(request()->param('pagesize'));
        $query = \trim(request()->param('query'));
        if (empty($pagenum) || empty($pagesize)) {
            return \show(config('status.error'), '传输数据为空', null);
        }

        $userObj = new ModelUser();
        $userList = $userObj->getUserList($pagenum, $pagesize, $query)->toArray();
        $Total = $userObj->getUserTotal($query);
        $res["list"] = $userList;
        $res["total"] = $Total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getUser()
    {
        $id =  \trim(request()->param('id'));
        if (empty($id)) {
            return \show(config('status.error'), '传输数据为空', null);
        }
        $userObj = new ModelUser();
        $res =$userObj->getUserById($id)->toArray();
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function changeStatus()
    {
        $userid = trim(request()->param('userid'));
        $status = trim(request()->param('status'));
        $userObj = new ModelUser();
        $res = $userObj->updateStatusByid($userid, $status); //返回0或1
        if (!$res || empty($res)) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }


    public function add()
    {
        $user = Request::param();
        $userObj = new ModelUser();
        $user['password'] = passwordMd5($user['password']);
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
        $id = Request::param('id');
        $userObj = new ModelUser();
        $res = $userObj->delete($id);//单个或批量删除
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }


}
