<?php
namespace app\common\model;

use think\Model;

class Dept extends Model
{
    public function getDeptList($query)
    {
        if(empty($query)){
        $res = $this->select();
        }else{
            $where=[
                'title'=>$query
            ];
        $res = $this->where($where)->find();
        }

        return $res;
    }

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

    public function getDeptByDeptName($DeptName)
    {
        if(empty($DeptName)){
             return false;
        }

        $where = [
            'DeptName' => $DeptName
        ];

        $res = $this->where($where)->find();

        return $res;
       
    }

    public function updateDeptByDeptName($DeptName,$info){
        if(empty($DeptName)){
            return false;
       }

       $where = [
        'DeptName' => $DeptName
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
                    'DeptName'=>$query
                ];
            $res = $this->where($where)->select()->count();
            }
        return $res;
    }

    public function updateStatusById($Deptid,$status)
    {
        if(empty($Deptid)){
         return false;
        }

        $where = [
            'id' => $Deptid
        ];

        $Dept = $this->where($where)->find();
        $Dept->status = $status; 
        $res = $Dept->save();
        return $res;
        
    }

    public function updateById($Deptid,$Dept)
    {
        if(empty($Deptid)){
         return false;
        }

        $where = [
            'id' => $Deptid
        ];
        $admin=$this->where($where)->find();
        if($Dept['password']==''){ 
           $admin->DeptName=$Dept['DeptName'];
           $admin->updated=date("Y-m-d h:i:s",time());
           $admin->mobile=$Dept['mobile'];
        }else{
            $admin->DeptName=$Dept['DeptName'];
            $admin->password=\passwordMd5($Dept['password']);
            $admin->updated=date("Y-m-d h:i:s",time());
            $admin->mobile=$Dept['mobile'];
        }

        $res = $admin->save();
        return $res;
        
    }
    
}