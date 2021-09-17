<?php
namespace app\common\model;

use think\Model;

class Leaves extends Model
{

    public function getLeavesList($pagenum,$pagesize,$query)
    {
        if(empty($pagenum)&&empty($pagesize)){
             return false;
        }

        if(empty($query)){
        $res = $this->limit(($pagenum-1)*$pagesize,$pagesize)->select();
        }else{
            $where=[
                'title'=>$query
            ];
        $res = $this->where($where)->limit(($pagenum-1)*$pagesize,$pagesize)->select();
        }

        return $res;
       
    }

    public function getLeavesById($id)
    {
        if(empty($id)){
             return false;
        }

        $where = [
            'id' => $id
        ];

        $res = $this->where($where)->find();

        return $res;
       
    }

    public function getLeavesByLeavesName($LeavesName)
    {
        if(empty($LeavesName)){
             return false;
        }

        $where = [
            'LeavesName' => $LeavesName
        ];

        $res = $this->where($where)->find();

        return $res;
       
    }

    public function updateLeavesByLeavesName($LeavesName,$info){
        if(empty($LeavesName)){
            return false;
       }

       $where = [
        'LeavesName' => $LeavesName
    ];

       $res = $this->where($where)->update($info);
       if($res){
           $res = $this->where($where)->find();
       }else{
           return false;
       }
       return $res;
    }

    public function getLeavesTotal($query)
    { 
        if(empty($query)){
            $res = $this->select()->count();
            }else{
                $where=[
                    'LeavesName'=>$query
                ];
            $res = $this->where($where)->select()->count();
            }
        return $res;
    }

    public function updateStatusById($Leavesid,$status)
    {
        if(empty($Leavesid)){
         return false;
        }

        $where = [
            'id' => $Leavesid
        ];

        $Leaves = $this->where($where)->find();
        $Leaves->status = $status; 
        $res = $Leaves->save();
        return $res;
        
    }

    public function updateById($Leavesid,$Leaves)
    {
        if(empty($Leavesid)){
         return false;
        }

        $where = [
            'id' => $Leavesid
        ];
        $admin=$this->where($where)->find();
        if($Leaves['password']==''){ 
           $admin->LeavesName=$Leaves['LeavesName'];
           $admin->updated=date("Y-m-d h:i:s",time());
           $admin->mobile=$Leaves['mobile'];
        }else{
            $admin->LeavesName=$Leaves['LeavesName'];
            $admin->password=\passwordMd5($Leaves['password']);
            $admin->updated=date("Y-m-d h:i:s",time());
            $admin->mobile=$Leaves['mobile'];
        }

        $res = $admin->save();
        return $res;
        
    }
    
}