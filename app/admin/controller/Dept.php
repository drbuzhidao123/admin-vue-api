<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\dept as ModelDept;
use think\facade\Request;

class Dept extends Base
{
    public function getDeptList()
    {
        $pagenum =  \trim(request()->param('pagenum'));
        $pagesize = \trim(request()->param('pagesize'));
        $query = \trim(request()->param('query'));
        if (empty($pagenum) || empty($pagesize)) {
            return \show(config('status.error'), '传输数据为空', null);
        }

        $deptObj = new ModelDept();
        $deptList = $deptObj->getDeptList($pagenum, $pagesize, $query)->toArray();
        $Total = $deptObj->getDeptTotal($query);
        $res["list"] = $deptList;
        $res["total"] = $Total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getDept()
    {
        $deptId =  \trim(request()->param('deptId'));
        if (empty($deptId)) {
            return \show(config('status.error'), '传输数据为空', null);
        }
        $deptObj = new ModelDept();
        $res =$deptObj->getDeptById($deptId)->toArray();
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function changeStatus()
    {
        $deptId = trim(request()->param('deptId'));
        $status = trim(request()->param('status'));
        $deptObj = new ModelDept();
        $res = $deptObj->updateStatusByid($deptId, $status); //返回0或1
        if (!$res || empty($res)) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }


    public function add()
    {
        $dept = Request::param();
        $deptObj = new ModelDept();
        $dept['password'] = passwordMd5($dept['password']);
        $res = $deptObj->save($dept); //返回boolse值
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function edit()
    {
        $dept = Request::param();
        $deptObj = new ModelDept();
        $res = $deptObj->updateById($dept['id'], $dept);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function remove()
    {
        $id = Request::param('id');
        $deptObj = new ModelDept();
        $res = $deptObj->delete($id);//单个或批量删除
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }


}
