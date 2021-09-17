<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\model\Leaves as ModelLeaves;
use think\facade\Request;

class Leaves extends Base
{
    public function getLeavesList()
    {
        $pagenum =  \trim(request()->param('pagenum'));
        $pagesize = \trim(request()->param('pagesize'));
        $query = \trim(request()->param('query'));
        if (empty($pagenum) || empty($pagesize)) {
            return \show(config('status.error'), '传输数据为空', null);
        }

        $leavesObj = new ModelLeaves();
        $leavesList = $leavesObj->getLeavesList($pagenum, $pagesize, $query)->toArray();
        $Total = $leavesObj->getLeavesTotal($query);
        $res["list"] = $leavesList;
        $res["total"] = $Total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getLeaves()
    {
        $leavesId =  \trim(request()->param('leavesId'));
        if (empty($leavesId)) {
            return \show(config('status.error'), '传输数据为空', null);
        }
        $leavesObj = new ModelLeaves();
        $res =$leavesObj->getLeavesById($leavesId)->toArray();
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function changeStatus()
    {
        $leavesId = trim(request()->param('leavesId'));
        $status = trim(request()->param('status'));
        $leavesObj = new ModelLeaves();
        $res = $leavesObj->updateStatusByid($leavesId, $status); //返回0或1
        if (!$res || empty($res)) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }


    public function add()
    {
        $leaves = Request::param();
        $leavesObj = new ModelLeaves();
        $leaves['password'] = passwordMd5($leaves['password']);
        $res = $leavesObj->save($leaves); //返回boolse值
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function edit()
    {
        $leaves = Request::param();
        $leavesObj = new ModelLeaves();
        $res = $leavesObj->updateById($leaves['id'], $leaves);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function remove()
    {
        $id = Request::param('id');
        $leavesObj = new ModelLeaves();
        $res = $leavesObj->delete($id);//单个或批量删除
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }


}
