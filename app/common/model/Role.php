<?php
namespace app\common\model;

use think\Model;

class Role extends Model
{

    public function getRoleById($id)
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

    public function getRoleByRoleName($RoleName)
    {
        if(empty($RoleName)){
             return false;
        }

        $where = [
            'RoleName' => $RoleName
        ];

        $res = $this->where($where)->find();

        return $res;
       
    }

    public function updateRoleByRoleName($RoleName,$info){
        if(empty($RoleName)){
            return false;
       }

       $where = [
        'RoleName' => $RoleName
    ];

       $res = $this->where($where)->update($info);
       if($res){
           $res = $this->where($where)->find();
       }else{
           return false;
       }
       return $res;
    }

    public function getRoleTotal($query)
    { 
        if(empty($query)){
            $res = $this->select()->count();
            }else{
                $where=[
                    'RoleName'=>$query
                ];
            $res = $this->where($where)->select()->count();
            }
        return $res;
    }

    public function updateStatusById($Roleid,$status)
    {
        if(empty($Roleid)){
         return false;
        }

        $where = [
            'id' => $Roleid
        ];

        $Role = $this->where($where)->find();
        $Role->status = $status; 
        $res = $Role->save();
        return $res;
        
    }

    public function updateById($Roleid,$Role)
    {
        if(empty($Roleid)){
         return false;
        }

        $where = [
            'id' => $Roleid
        ];
        $admin=$this->where($where)->find();
        if($Role['password']==''){ 
           $admin->RoleName=$Role['RoleName'];
           $admin->updated=date("Y-m-d h:i:s",time());
           $admin->mobile=$Role['mobile'];
        }else{
            $admin->RoleName=$Role['RoleName'];
            $admin->password=\passwordMd5($Role['password']);
            $admin->updated=date("Y-m-d h:i:s",time());
            $admin->mobile=$Role['mobile'];
        }

        $res = $admin->save();
        return $res;
        
    }
    
}