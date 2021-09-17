<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use app\BaseController;
use app\common\controller\Tool;
use app\common\model\Menu as ModelMenu;
use app\common\model\Role;
use think\Request;

class Menu extends BaseController
{
    public function getMenuList()
    {
        $query = trim(request()->param('query'));
        $menuObj = new ModelMenu();
        $menuList = $menuObj->getMenuList($query)->toArray();
        $tool = new Tool();
        $res = $tool->tree($menuList, 0);
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getMenuListByUserId(Request $request)
    {
        $userId = $request->param->userId;
        if (empty($userId)) {
            return show(config('status.error'), '传输数据为空', null);
        }
        $roleObj = new Role();
        $menuObj = new ModelMenu();
        $tool = new Tool();
        $role = $roleObj->getRoleByUserId($userId)->toArray();
        $permissionList = explode(",", $role["permissionList"]);
        $menuList = $menuObj->select($permissionList)->toArray();
        $res = $tool->tree($menuList,0);
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getMenu()
    {
        $menuId =  trim(request()->param('menuId'));
        if (empty($id)) {
            return \show(config('status.error'), '传输数据为空', null);
        }
        $menuObj = new ModelMenu();
        $res = $menuObj->getMenuById($menuId)->toArray();
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function changeStatus()
    {
        $menuId = trim(request()->param('menuId'));
        $status = trim(request()->param('status'));
        $menuObj = new ModelMenu();
        $res = $menuObj->updateStatusByid($menuId, $status); //返回0或1
        if (!$res || empty($res)) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }


    public function add()
    {
        $menu = Request::param();
        $menuObj = new ModelMenu();
        $Menu['password'] = passwordMd5($menu['password']);
        $res = $menuObj->save($menu); //返回boolse值
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function edit()
    {
        $menu = Request::param();
        $menuObj = new ModelMenu();
        $res = $menuObj->updateById($menu['id'], $menu);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function remove()
    {
        $menuId = Request::param('id');
        $menuObj = new ModelMenu();
        $res = $menuObj->delete($menuId); //单个或批量删除
        if (empty($res)) {
            return show(config('status.error'), '删除失败', $res);
        }
        return show(config('status.success'), '删除成功', $res);
    }
}
