<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\common\lib\IdWork;
use app\common\model\Dept;
use app\common\model\Leaves as ModelLeaves;
use think\Request;

class Leaves extends Base
{
    //获取列表
    public function getLeavesList(Request $request)
    {
        $param = (array)($request->param);
        if (empty($param['pageNum']) || empty($param['pageSize'])||empty($param['applyUser'])) {
            return show(config('status.error'), '没有传输数据', null);
        }
        $leavesObj = new ModelLeaves();
        $leavesList = $leavesObj->getLeavesList($param['pageNum'], $param['pageSize'],$param['applyState'],$param['applyUser']);
        $total = $leavesObj->getLeavesTotal($param['applyState'],$param['applyUser']);
        $res["list"] = $leavesList;
        $res["total"] = $total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }
    //申请请假
    public function addLeaves(Request $request)
    {
        $param = (array)($request->param);
        if($param['applyUser']==1){
            return show(config('status.error'), '您是ceo，不用申请', 0);
        }
        $leavesObj = new ModelLeaves();
        $deptObj = new Dept();
        $workId = rand(1, 1023);//随机数
        $param['orderNo'] = IdWork::getInstance()->setWorkId($workId)->nextId();//雪花算法生成唯一订单编号
        $param['orderNo'] = (string)$param['orderNo'];
        $deptId = end($param['deptId']);//所属部门
        unset($param['deptId']);
        $dept=$deptObj-> getDeptById($deptId);
        $param['curAuditUserName'] = $dept['userName'];
        $param['createTime'] = date('Y-m-d h:i:s', time());
        $param['auditFlows'] =  implode(',',[$dept['userName'],"mcdull","admin"]);//完整审批流
        $res =  $leavesObj->save($param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }
    //撤回申请
    public function delLeaves(Request $request)
    {
        $param = (array)($request->param);
        $leavesObj = new ModelLeaves();
        $res = $leavesObj->where('orderNo',$param['orderNo'])->update(['applyState'=>5]);
        if (!$res) {
            return show(config('status.error'), '撤回失败', $res);
        }
        return show(config('status.success'), '撤回成功', $res);
      
    }

    //审核人看的列表
    public function getApproveList(Request $request)
    {
        $param = (array)($request->param);
        if (empty($param['pageNum']) || empty($param['pageSize'])||empty($param['applyUser'])) {
            return show(config('status.error'), '没有传输数据', null);
        }
        $leavesObj = new ModelLeaves();
        $leavesList = $leavesObj->getLeavesList($param['pageNum'], $param['pageSize'],$param['applyState'],$param['applyUser']);
        $total = $leavesObj->getLeavesTotal($param['applyState'],$param['applyUser']);
        $res["list"] = $leavesList;
        $res["total"] = $total;
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }


}
