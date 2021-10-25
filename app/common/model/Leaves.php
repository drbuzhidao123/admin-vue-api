<?php

namespace app\common\model;

use think\Model;

class Leaves extends Model
{

    public function getLeavesList($pagenum, $pagesize, $applyState, $applyUser)
    {
        if (empty($pagenum) && empty($pagesize)) {
            return false;
        }

        if (empty($applyState)) {
            //全部
            $where = [
                'applyUser' => $applyUser
            ];
            $res = $this->where($where)->limit(($pagenum - 1) * $pagesize, $pagesize)->select()->toArray();
        } else {
            $where = [
                'applyState' => $applyState,
                'applyUser' => $applyUser
            ];
            $res = $this->where($where)->limit(($pagenum - 1) * $pagesize, $pagesize)->select()->toArray();
        }

        return $res;
    }

    public function getApproveListByCur($pagenum, $pagesize, $applyState, $curAuditUserName)
    {
        if (empty($curAuditUserName)) {
            return false;
        }
        $where = [
            'applyState' => $applyState,
            'curAuditUserName' => $curAuditUserName
        ];
        $res = $this->alias('l')->leftjoin('user u','l.applyUser=u.id')->where($where)->limit(($pagenum - 1) * $pagesize, $pagesize)->select()->toArray();
        return $res;
    }

    public function getApproveListByFlow($pagenum, $pagesize, $applyState, $userName)
    {
        if (empty($userName)) {
            return false;
        }
        $res = $this->alias('l')->leftjoin('user u','l.applyUser=u.id')->where('applyState', $applyState)->where('auditFlows', 'like', '%' . $userName . '%')->limit(($pagenum - 1) * $pagesize, $pagesize)->select()->toArray();
        return $res;
    }

    public function getApproveList($pagenum, $pagesize, $userName)
    {
        if (empty($userName)) {
            return false;
        }
        $res = $this->alias('l')->leftjoin('user u','l.applyUser=u.id')->where('auditFlows', 'like', '%' . $userName . '%')->limit(($pagenum - 1) * $pagesize, $pagesize)->select()->toArray();
        return $res;
    }

    public function getLeavesTotal($applyState, $applyUser)
    {
        if (empty($applyState)) {
            //全部
            $where = [
                'applyUser' => $applyUser
            ];
            $res = $this->where($where)->select()->count();
        } else {
            $where = [
                'applyState' => $applyState,
                'applyUser' => $applyUser
            ];
            $res = $this->where($where)->select()->count();
        }
        return $res;
    }


    public function getApproveTotalByCur($applyState, $curAuditUserName)
    {
        $where = [
            'applyState' => $applyState,
            'curAuditUserName' => $curAuditUserName
        ];
        $res = $this->where($where)->select()->count();
        return $res;
    }

    public function getApproveTotalByFlow($applyState, $userName)
    {
        $res = $this->where('applyState', $applyState)->where('auditFlows', 'like', '%' . $userName . '%')->select()->count();
        return $res;
    }

    public function getApproveTotal($userName)
    {
        $res = $this->where('auditFlows', 'like', '%' . $userName . '%')->select()->count();
        return $res;
    }
}
