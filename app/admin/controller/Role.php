<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\Role as ModelRole;
use think\facade\Request;

class Role extends Base
{
    public function getRoleList()
    {
        $pagenum =  \trim(request()->param('pagenum'));
        $pagesize = \trim(request()->param('pagesize'));
        $query = \trim(request()->param('query'));
        if (empty($pagenum) || empty($pagesize)) {
            return \show(config('status.error'), '传输数据为空', null);
        }

        $roleObj = new ModelRole();
        $roleList = $roleObj->getRoleList($pagenum, $pagesize, $query)->toArray();
        $Total = $roleObj->getRoleTotal($query);
        $res["list"] = $roleList;
        $res["total"] = $Total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getRole()
    {
        $roleId =  \trim(request()->param('roleId'));
        if (empty($roleId)) {
            return \show(config('status.error'), '传输数据为空', null);
        }
        $roleObj = new ModelRole();
        $res =$roleObj->getRoleById($roleId)->toArray();
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function changeStatus()
    {
        $roleId = trim(request()->param('roleId'));
        $status = trim(request()->param('status'));
        $roleObj = new ModelRole();
        $res = $roleObj->updateStatusByid($roleId, $status); //返回0或1
        if (!$res || empty($res)) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }


    public function add()
    {
        $role = Request::param();
        $roleObj = new ModelRole();
        $role['password'] = passwordMd5($role['password']);
        $res = $roleObj->save($role); //返回boolse值
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function edit()
    {
        $role = Request::param();
        $roleObj = new ModelRole();
        $res = $roleObj->updateById($role['id'], $role);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function remove()
    {
        $roleId = Request::param('roleId');
        $roleObj = new ModelRole();
        $res = $roleObj->delete($roleId);//单个或批量删除
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }


}
