<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\controller\Tool;
use app\common\model\dept as ModelDept;
use think\Request;

class Dept extends Base
{
    public function getDeptList(Request $request)
    {
        $dept = (array)($request->param);
        $deptObj = new ModelDept();
        if(!$dept['query']){
            $tool = new Tool();
            $deptList = $deptObj->select()->toArray();
            $deptList=$tool->tree($deptList,1);
        }else{
            $deptList = $deptObj->getDeptByDeptName($dept['query'])->toArray();
        }
        $res = $deptList;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function addDept(Request $request)
    {
        $request->param->createTime = date('Y-m-d h:i:s', time());
        $request->param->updateTime = date('Y-m-d h:i:s', time());
        $dept = (array)($request->param);
        $deptObj = new ModelDept();
        $res = $deptObj->save($dept);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function editDept(Request $request)
    {
        $dept = (array)($request->param);
        $deptObj = new ModelDept();
        $res = $deptObj->updateById($dept['id'], $dept);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function delDept(Request $request)
    {
        $dept = (array)($request->param);
        $deptObj = new ModelDept();
        $res = $deptObj::destroy($dept['id']);//单个删除
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }


}
