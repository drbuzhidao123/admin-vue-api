<?php
namespace app\common\model;

use think\Model;

class Leaves extends Model
{

    public function getLeavesList($pagenum,$pagesize,$applyState,$applyUser)
    {
        if(empty($pagenum)&&empty($pagesize)){
             return false;
        }

        if(empty($applyState)){
        //全部
        $where=[
            'applyUser'=>$applyUser
        ];
        $res = $this->where($where)->limit(($pagenum-1)*$pagesize,$pagesize)->select()->toArray();
        }else{
            $where=[
                'applyState'=>$applyState,
                'applyUser'=>$applyUser
            ];
        $res = $this->where($where)->limit(($pagenum-1)*$pagesize,$pagesize)->select()->toArray();
        }

        return $res;
       
    }

    public function getLeavesTotal($applyState,$applyUser)
    { 
        if(empty($applyState)){
            //全部
            $where=[
                'applyUser'=>$applyUser
            ];
            $res = $this->where($where)->select()->count();
            }else{
                $where=[
                    'applyState'=>$applyState,
                    'applyUser'=>$applyUser
                ];
            $res = $this->where($where)->select()->count();
            }
        return $res;
    }
    
}