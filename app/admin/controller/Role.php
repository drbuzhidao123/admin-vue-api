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

        $RoleObj = new ModelRole();
        $RoleList = $RoleObj->getRoleList($pagenum, $pagesize, $query)->toArray();
        $Total = $RoleObj->getRoleTotal($query);
        $res["list"] = $RoleList;
        $res["total"] = $Total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getRole()
    {
        $id =  \trim(request()->param('id'));
        if (empty($id)) {
            return \show(config('status.error'), '传输数据为空', null);
        }
        $RoleObj = new ModelRole();
        $res =$RoleObj->getRoleById($id)->toArray();
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function changeStatus()
    {
        $Roleid = trim(request()->param('Roleid'));
        $status = trim(request()->param('status'));
        $RoleObj = new ModelRole();
        $res = $RoleObj->updateStatusByid($Roleid, $status); //返回0或1
        if (!$res || empty($res)) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }


    public function add()
    {
        $Role = Request::param();
        $RoleObj = new ModelRole();
        $Role['password'] = passwordMd5($Role['password']);
        $res = $RoleObj->save($Role); //返回boolse值
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function edit()
    {
        $Role = Request::param();
        $RoleObj = new ModelRole();
        $res = $RoleObj->updateById($Role['id'], $Role);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function remove()
    {
        $id = Request::param('id');
        $RoleObj = new ModelRole();
        $res = $RoleObj->delete($id);//单个或批量删除
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }


}
