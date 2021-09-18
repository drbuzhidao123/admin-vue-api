<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\dept as ModelDept;
use think\Request;

class Dept extends Base
{
    public function getDeptList(Request $request)
    {
        return show(config('status.success'), '更新成功', $request->param("data"));
        $query = trim($request->param->query);
        $deptObj = new ModelDept();
        $deptList = $deptObj->getDeptList($query)->toArray();
        $res = $deptList;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function addDept(Request $request)
    {
        $dept = $request->param;
        $deptObj = new ModelDept();
        $res = $deptObj->save($dept);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function editDept()
    {
        $dept = Request::param();
        $deptObj = new ModelDept();
        $res = $deptObj->updateById($dept['id'], $dept);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function delDept()
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
