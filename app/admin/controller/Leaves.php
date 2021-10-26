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
        if (empty($param['pageNum']) || empty($param['pageSize']) || empty($param['applyUser'])) {
            return show(config('status.error'), '没有传输数据', null);
        }
        $leavesObj = new ModelLeaves();
        $leavesList = $leavesObj->getLeavesList($param['pageNum'], $param['pageSize'], $param['applyState'], $param['applyUser']);
        $total = $leavesObj->getLeavesTotal($param['applyState'], $param['applyUser']);
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
        if ($param['applyUser'] == 1) {
            return show(config('status.error'), '您是ceo，不用申请', 0);
        }
        $leavesObj = new ModelLeaves();
        $deptObj = new Dept();
        $workId = rand(1, 1023); //随机数
        $param['orderNo'] = IdWork::getInstance()->setWorkId($workId)->nextId(); //雪花算法生成唯一订单编号
        $param['orderNo'] = (string)$param['orderNo'];
        $deptId = end($param['deptId']); //所属部门
        unset($param['deptId']);
        $dept = $deptObj->getDeptById($deptId);
        $param['curAuditUserName'] = $dept['userName'];
        $param['createTime'] = date('Y-m-d h:i:s', time());
        $param['auditFlows'] =  implode(',', [$dept['userName'], "mcdull", "admin"]); //完整审批流
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
        $res = $leavesObj->where('orderNo', $param['orderNo'])->update(['applyState' => 5,'curAuditUserName'=>'']);
        if (!$res) {
            return show(config('status.error'), '撤回失败', $res);
        }
        return show(config('status.success'), '撤回成功', $res);
    }

    //审核人看的列表
    public function getApproveList(Request $request)
    {
        $param = (array)($request->param);
        $leavesObj = new ModelLeaves();
        if (empty($param['pageNum']) || empty($param['pageSize'])) {
            return show(config('status.error'), '没有传输数据', $param);
        }
        if ($param['applyState'] == 1 || $param['applyState'] == 2) {
            $leavesList = $leavesObj->getApproveListByCur($param['pageNum'], $param['pageSize'], $param['applyState'], $param['userName']);
            $total = $leavesObj->getApproveTotalByCur($param['applyState'], $param['userName']);
            $res["list"] = $leavesList;
            $res["total"] = $total;
        } else if ($param['applyState'] > 2) {
            $leavesList = $leavesObj->getApproveListByFlow($param['pageNum'], $param['pageSize'], $param['applyState'], $param['userName']);
            $total = $leavesObj->getApproveTotalByFlow($param['applyState'], $param['userName']);
            $res["list"] = $leavesList;
            $res["total"] = $total;
        } else {
            $leavesList = $leavesObj->getApproveList($param['pageNum'], $param['pageSize'], $param['userName']);
            $total = $leavesObj->getApproveTotal($param['userName']);
            $res["list"] = $leavesList;
            $res["total"] = $total;
        }
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    //审批
    public function leaveApprove(Request $request)
    {
        $param = (array)($request->param);
        if (empty($param['orderNo']) || empty($param['action'])) {
            return show(config('status.error'), '没有传输订单号或审批动作参数', $param);
        }
        $query = [];
        $leavesObj = new ModelLeaves();
        $leaves = $leavesObj->where('orderNo',$param['orderNo'])->find();
        $auditFlows = explode(',',$leaves['auditFlows']);
        if(empty($leaves['auditLogs'])){
            $auditLogs = [];
        }else{
            $auditLogs = json_decode($leaves['auditLogs']);
        }
        if($param['action']=='refuse'){
            //审核拒绝
             $query['applyState'] = 3;
             $query['curAuditUserName'] = "";
        }else{
            //审核通过
            if(count($auditFlows)==count($auditLogs)){
                return show(config('status.error'), '当前单号已处理，请不要重复提交', null);
            }else if(count($auditFlows)-count($auditLogs)>1){
                $query['applyState'] = 2;
                $query['curAuditUserName'] =  $auditFlows[count($auditLogs)+1];
            }else{
                //最后一个节点
                $query['applyState']=4;
                $query['curAuditUserName'] = "";
            }
        }
        $log = ['userName'=>$param['approveUser'],'remark'=>$param['remark']];
        array_push($auditLogs,$log);
        $query['auditLogs']=json_encode($auditLogs);
        $res = $leavesObj->where('orderNo',$param['orderNo'])->update($query);
        if (!$res) {
            return show(config('status.error'), '审批失败', $res);
        }
        return show(config('status.success'), '审批成功', $res);
    }

    //任务提示
    public function noticeCount(Request $request)
    {
        $param = (array)($request->param);
        $leavesObj = new ModelLeaves();
        $where = [
            'curAuditUserName' => $param['userName']
        ];
        $res = $leavesObj->where($where)->count();  
        return show(config('status.success'), '查询数据成功', $res);
    }
     //申请数量
     public function leavesCount(Request $request)
     {
         $param = (array)($request->param);
         $leavesObj = new ModelLeaves();
         $where = [
             'applyUser' => $param['applyUser']
         ];
         $res = $leavesObj->where($where)->count();  
         return show(config('status.success'), '查询数据成功', $res);
     }
}
