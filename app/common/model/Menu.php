<?php
namespace app\common\model;

use think\Model;

class Menu extends Model
{
    
    public function getMenuList($query)
    {
        if(empty($query)){
        $res = $this->select()->toArray();
        }else{
            $where=[
                'menuName'=>$query
            ];
        $res = $this->where($where)->select()->toArray();
        }

        return $res;
       
    }

    public function getMenuById($id)
    {
        if(empty($id)){
             return false;
        }

        $where = [
            'id' => $id
        ];

        $res = $this->where($where)->find()->toArray();

        return $res;
       
    }

    public function getMenuByMenuName($menuName)
    {
        if(empty($menuName)){
             return false;
        }

        $where = [
            'menuName' => $menuName
        ];

        $res = $this->where($where)->find()->toArray();

        return $res;
       
    }

    public function updateMenuByMenuName($menuName,$info){
        if(empty($menuName)){
            return false;
       }

       $where = [
        'menuName' => $menuName
    ];

       $res = $this->where($where)->update($info);
       if($res){
           $res = $this->where($where)->find()->toArray();
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
                    'menuName'=>$query
                ];
            $res = $this->where($where)->select()->count();
            }
        return $res;
    }

    public function updateStatusById($menuid,$status)
    {
        if(empty($menuid)){
         return false;
        }

        $where = [
            'id' => $menuid
        ];

        $Menu = $this->where($where)->find();
        $Menu->status = $status; 
        $res = $Menu->save();
        return $res;
        
    }
    
}