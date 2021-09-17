<?php
namespace app\common\model;

use think\Model;

class Menu extends Model
{
    
    public function getMenuList($query)
    {
        if(empty($query)){
        $res = $this->select();
        }else{
            $where=[
                'title'=>$query
            ];
        $res = $this->where($where)->select();
        }

        return $res;
       
    }

    public function getMenuListByUserId($userId)
    {
         
    }

    public function getMenuById($id)
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

    public function getMenuByMenuName($MenuName)
    {
        if(empty($MenuName)){
             return false;
        }

        $where = [
            'MenuName' => $MenuName
        ];

        $res = $this->where($where)->find();

        return $res;
       
    }

    public function updateMenuByMenuName($MenuName,$info){
        if(empty($MenuName)){
            return false;
       }

       $where = [
        'MenuName' => $MenuName
    ];

       $res = $this->where($where)->update($info);
       if($res){
           $res = $this->where($where)->find();
       }else{
           return false;
       }
       return $res;
    }

    public function getMenuTotal($query)
    { 
        if(empty($query)){
            $res = $this->select()->count();
            }else{
                $where=[
                    'MenuName'=>$query
                ];
            $res = $this->where($where)->select()->count();
            }
        return $res;
    }

    public function updateStatusById($Menuid,$status)
    {
        if(empty($Menuid)){
         return false;
        }

        $where = [
            'id' => $Menuid
        ];

        $Menu = $this->where($where)->find();
        $Menu->status = $status; 
        $res = $Menu->save();
        return $res;
        
    }

    public function updateById($Menuid,$Menu)
    {
        if(empty($Menuid)){
         return false;
        }

        $where = [
            'id' => $Menuid
        ];
        $admin=$this->where($where)->find();
        if($Menu['password']==''){ 
           $admin->MenuName=$Menu['MenuName'];
           $admin->updated=date("Y-m-d h:i:s",time());
           $admin->mobile=$Menu['mobile'];
        }else{
            $admin->MenuName=$Menu['MenuName'];
            $admin->password=\passwordMd5($Menu['password']);
            $admin->updated=date("Y-m-d h:i:s",time());
            $admin->mobile=$Menu['mobile'];
        }

        $res = $admin->save();
        return $res;
        
    }
    
}