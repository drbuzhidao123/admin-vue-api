<?php
namespace app\common\model;

use think\Model;

class User extends Model
{

    public function getUserList($pagenum,$pagesize,$query)
    {
        if(empty($pagenum)&&empty($pagesize)){
             return false;
        }

        if(empty($query)){
        $res = $this->limit(($pagenum-1)*$pagesize,$pagesize)->select()->toArray();
        }else{
            $where=[
                'title'=>$query
            ];
        $res = $this->where($where)->limit(($pagenum-1)*$pagesize,$pagesize)->select()->toArray();
        }

        return $res;
       
    }

    public function getUserById($id)
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

    public function getUserByuserName($userName)
    {
        if(empty($userName)){
             return false;
        }

        $where = [
            'userName' => $userName
        ];

        $res = $this->where($where)->find()->toArray();

        return $res;
       
    }

    public function updateUserByuserName($userName,$info){
        if(empty($userName)){
            return false;
       }

       $where = [
        'userName' => $userName
    ];

       $res = $this->where($where)->update($info);
       if($res){
           $res = $this->where($where)->find()->toArray();
       }else{
           return false;
       }
       return $res;
    }

    public function getUserTotal($query)
    { 
        if(empty($query)){
            $res = $this->select()->count();
            }else{
                $where=[
                    'userName'=>$query
                ];
            $res = $this->where($where)->select()->count();
            }
        return $res;
    }

    public function updateStatusById($userid,$status)
    {
        if(empty($userid)){
         return false;
        }

        $where = [
            'id' => $userid
        ];

        $user = $this->where($where)->find()->toArray();
        $user->status = $status; 
        $res = $user->save();
        return $res;
        
    }

    public function updateById($userid,$user)
    {
        if(empty($userid)){
         return false;
        }

        $where = [
            'id' => $userid
        ];
        $admin=$this->where($where)->find()->toArray();
        if($user['password']==''){ 
           $admin->userName=$user['userName'];
           $admin->updated=date("Y-m-d h:i:s",time());
           $admin->mobile=$user['mobile'];
        }else{
            $admin->userName=$user['userName'];
            $admin->password=\passwordMd5($user['password']);
            $admin->updated=date("Y-m-d h:i:s",time());
            $admin->mobile=$user['mobile'];
        }

        $res = $admin->save();
        return $res;
        
    }
    
}