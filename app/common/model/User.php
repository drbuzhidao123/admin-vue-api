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
        $res = $this->alias('u')->leftjoin('role r','u.role=r.id')->field('u.id,u.userName,u.userEmail,u.mobile,u.sex,u.deptId,u.job,u.status,u.role,u.createTime,u.updateTime,r.roleName')->limit(($pagenum-1)*$pagesize,$pagesize)->select()->toArray();
        }else{
            $where=[
                'u.userName'=>$query,
            ];
        $res = $this->alias('u')->leftjoin('role r','u.role=r.id')->where($where)->field('u.id,u.userName,u.userEmail,u.mobile,u.sex,u.deptId,u.job,u.status,u.role,u.createTime,u.updateTime,r.roleName')->limit(($pagenum-1)*$pagesize,$pagesize)->select()->toArray();
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

        $res = $this->where($where)->find();

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

    public function updateStatusById($userId,$status)
    {
        if(empty($userId)){
         return false;
        }

        $where = [
            'id' => $userId
        ];

        $user = $this->where($where)->find();
        $user->status = $status; 
        $res = $user->save();
        return $res;
        
    }

    public function updateById($userId,$param)
    {
        if(empty($userId)){
         return false;
        }

        $where = [
            'id' => $userId
        ];
        $user=$this->where($where)->find();
        $param['updateTime'] = date('Y-m-d h:i:s', time());
        $res=$user->where($where)->update($param);
        return $res;
        
    }
    
}