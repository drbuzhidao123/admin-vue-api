<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\User as ModelUser;
use think\facade\Db;
use think\Request;

class User extends Base
{
    public function getUserList(Request $request)
    {
        $param = (array)($request->param);
        if (empty($param['pageNum']) || empty($param['pageSize'])) {
            return show(config('status.error'), '传输数据为空', null);
        }
        $userObj = new ModelUser();
        $userList = $userObj->getUserList($param['pageNum'], $param['pageSize'],$param['query']);
        $Total = $userObj->getUserTotal($param['query']);
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

    public function getUser(Request $request)
    {
        $param = (array)($request->param);
        $userId =  \trim($param('userId'));
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

    public function changeStatus(Request $request)
    {
        $param = (array)($request->param);
        $userId = trim($param['id']);
        $status = trim($param['status']);
        $userObj = new ModelUser();
        $res = $userObj->updateStatusByid($userId, $status); 
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function addUser(Request $request)
    {
        $param = (array)($request->param);
        $userObj = new ModelUser();
        $hasUser=$userObj->where(['userName'=>$param['userName']])->find();
        if(!empty($hasUser)){
            return show(config('status.error'), '用户名已经存在', null);
        }
        $param['password'] = passwordMd5($param['password']);
        $param['deptId'] = implode(',', $param['deptId']);
        $param['createTime'] = date('Y-m-d h:i:s', time());
        $param['updateTime'] = date('Y-m-d h:i:s', time());
        $res = $userObj->save($param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function editUser(Request $request)
    {
        $param = (array)($request->param);
        $userObj = new ModelUser();
        $param['deptId'] = implode(',', $param['deptId']);
        $param['updateTime'] = date('Y-m-d h:i:s', time());
        if(!empty($param['password'])){
            $param['password'] = passwordMd5($param['password']); 
        }
        $res = $userObj->update($param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function delUser(Request $request)
    {
        $param = (array)($request->param);
        $param['id'] = trim($param['id']);
        $userObj = new ModelUser();
        $res = $userObj->where('id',$param['id'])->delete();
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }

    public function delManyUser(Request $request)
    {
        $param = (array)($request->param);
        $res = Db::table('user')->delete($param['userIds']);
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }

    public function getUserCount(Request $request)
    {
        $param = (array)($request->param);
        $userObj = new ModelUser();
        $res = $userObj->getUserTotal(null);
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }


}
