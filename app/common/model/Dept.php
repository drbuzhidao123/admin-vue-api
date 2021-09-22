<?php
namespace app\common\model;

use think\Model;

class Dept extends Model
{
    public function getDeptById($id)
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

    public function getDeptByDeptName($deptName)
    {
        if(empty($deptName)){
             return false;
        }

        $where = [
            'deptName' => $deptName
        ];

        $res = $this->where($where)->find();

        return $res;
       
    }

    public function updateDeptByDeptName($deptName,$info){
        if(empty($deptName)){
            return false;
       }

       $where = [
        'deptName' => $deptName
    ];

       $res = $this->where($where)->update($info);
       if($res){
           $res = $this->where($where)->find();
       }else{
           return false;
       }
       return $res;
    }

    public function getDeptTotal($query)
    { 
        if(empty($query)){
            $res = $this->select()->count();
            }else{
                $where=[
                    'deptName'=>$query
                ];
            $res = $this->where($where)->select()->count();
            }
        return $res;
    }

    public function updateStatusById($deptid,$status)
    {
        if(empty($deptid)){
         return false;
        }

        $where = [
            'id' => $deptid
        ];

        $deptData = $this->where($where)->find();
        $deptData->status = $status; 
        $res = $deptData->save();
        return $res;
        
    }

    public function updateById($deptid,$dept)
    {
        if(empty($deptid)){
         return false;
        }

        $where = [
            'id' => $deptid
        ];
        $deptData=$this->where($where)->find();
           $deptData->parentId=$dept['parentId'];
           $deptData->userId=$dept['userId'];
           $deptData->deptName=$dept['deptName'];
           $deptData->userName=$dept['userName'];
           $deptData->userEmail=$dept['userEmail'];
           $deptData->updateTime=date("Y-m-d h:i:s",time());
        $res = $deptData->save();
        return $res;
        
    }
    
}