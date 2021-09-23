<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\controller\Tool;
use app\common\model\dept as ModelDept;
use think\facade\Db;
use think\Request;

class Dept extends Base
{
    public function getDeptList(Request $request)
    {
        $param = (array)($request->param);
        $deptObj = new ModelDept();
        $tool = new Tool();
        if (!$param['query']) {
            $res = $deptObj->select()->toArray();
            $res = $tool->tree($res);
        } else {
            $res = $deptObj->getDeptByDeptName($param['query'])->toArray();
            return show(config('status.success'), '查询数据成功', $res);
        }
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function addDept(Request $request)
    {
        $param = (array)($request->param);
        $param['createTime'] = date('Y-m-d h:i:s', time());
        $param['updateTime'] = date('Y-m-d h:i:s', time());
        $param['parentId'] = implode(',', $param['parentId']);
        //return show(config('status.success'), '更新成功', $param);
        $deptObj = new ModelDept();
        $res = $deptObj->save($param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function editDept(Request $request)
    {
        $deptObj = new ModelDept();
        $param = (array)($request->param);
        $param['parentId'] = implode(',', $param['parentId']);
        $query1 = $param['parentId'];
        $query2 = $param['id'];
        //Db::execute("update dept set parentId='".$query1.",`parentId`'where parentId like '%".$query2."%'");
        $res = $deptObj->updateById($param['id'],  $param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function delDept(Request $request)
    {
        $param = (array)($request->param);
        $deptObj = new ModelDept();
        $res1 = $deptObj::where('parentId', 'like', '%' . $param['id'] . '%')->delete(); //批量删除
        $res2 = $deptObj::where('id', '=', $param['id'])->delete();
        return show(config('status.success'), '删除成功', true);
    }
}
