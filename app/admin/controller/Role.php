<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\Role as ModelRole;
use think\Request;

class Role extends Base
{
    public function getRoleList(Request $request)
    {
        $param = (array)($request->param);
        if (empty($param['pageNum']) || empty($param['pageSize'])) {
            return show(config('status.error'), '传输数据为空', null);
        }
        $roleObj = new ModelRole();
        $roleList = $roleObj->getRoleList($param['pageNum'], $param['pageSize'],$param['query']);
        $total = $roleObj->getRoleTotal($param['query']);
        $res["list"] = $roleList;
        $res["total"] = $total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function addRole(Request $request)
    {
        $param = (array)($request->param);
        $param['createTime'] = date('Y-m-d h:i:s', time());
        $param['updateTime'] = date('Y-m-d h:i:s', time());
        $roleObj = new ModelRole();
        $res =  $roleObj->save($param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function editRole(Request $request)
    {
        $param = (array)($request->param);
        $param['updateTime'] = date('Y-m-d h:i:s', time());
        $roleObj = new ModelRole();
        $res = $roleObj->update($param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function updatePermission(Request $request)
    {
        $param = (array)($request->param);
        $roleObj = new ModelRole();
        $param['permissionList'] = implode(',', $param['permissionList']);
        $param['checkedKeys'] = implode(',', $param['checkedKeys']);
        $param['halfCheckedKeys'] = implode(',', $param['halfCheckedKeys']);
        $res = $roleObj->update($param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function delRole(Request $request)
    {
        $param = (array)($request->param);
        $roleObj = new ModelRole();
        $res = $roleObj->where('id',$param['id'])->delete();
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }


}
