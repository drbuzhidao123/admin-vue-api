<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\controller\Tool;
use app\common\model\Menu as ModelMenu;
use app\common\model\Role;
use think\Request;

class Menu extends BaseController
{
    public function getMenuList(Request $request)
    {
        $param = (array)($request->param);
        $menuObj = new ModelMenu();
        $tool = new Tool();
        $res = $menuObj->getMenuList($param['query']);
        if (empty($param['query'])) {
            $res = $tool->tree($res);
        }
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function getMenuListByUserId(Request $request)
    {
        $param = (array)($request->param);
        $userId = $param['userId'];
        if (empty($userId)) {
            return show(config('status.error'), '传输数据为空', null);
        }
        $roleObj = new Role();
        $menuObj = new ModelMenu();
        $tool = new Tool();
        $role = $roleObj->getRoleByUserId($userId);
        $permissionList = array_merge(explode(",", $role["checkedKeys"]),explode(",", $role["halfCheckedKeys"]));
        $menuList = $menuObj->select($permissionList)->toArray();
        $res = $tool->tree($menuList);
        if (empty($res)) {
            return show(config('status.error'), '没有数据', $res);
        }
        return show(config('status.success'), '查询数据成功', $res);
    }

    public function addMenu(Request $request)
    {
        $param = (array)($request->param);
        $param['createTime'] = date('Y-m-d h:i:s', time());
        $param['parentId'] = implode(',', $param['parentId']);
        $menuObj = new ModelMenu();
        $res =  $menuObj->save($param);
        if (!$res) {
            return show(config('status.error'), '更新失败', $res);
        }
        return show(config('status.success'), '更新成功', $res);
    }

    public function editMenu(Request $request)
    {
        $param = (array)($request->param);
        $menuObj = new ModelMenu();
        $param['parentId'] = implode(',', $param['parentId']);
        $old = $menuObj->where('id', $param['id'])->find();
        $save = $menuObj->update($param);
        if (!$save) {
            return show(config('status.success'), '更新失败', $save);
        }
        if ($old['parentId'] !== $param['parentId']) { //当父级改动的时候
            if ($old['parentId'] == '') {
                $res = $menuObj
                    ->where('parentId', 'like', '%' . $param['id'] . '%')
                    ->exp('parentId', 'concat("' . $param['parentId'] . ',",parentId)')
                    ->update(); //如果父级是顶级，所有子级的parentId前面加新的parentId
            } else {
                $res = $menuObj
                    ->where('parentId', 'like', '%' . $param['id'] . '%')
                    ->exp('parentId', 'replace(parentId,"' . $old['parentId'] . '","' . $param['parentId'] . '")')
                    ->update(); //如果父级不是顶级，所有子级的parentId中原先部分更换成新的
            }
        }

        return show(config('status.success'), '更新成功', 200);
    }

    public function delMenu(Request $request)
    {
        $param = (array)($request->param);
        $menuObj = new ModelMenu();
        //$permissionId1 = $menuObj->where('parentId','like', '%' . $param['id'] . '%')->field('id')->select()->toArray();
        //$permissionId2 = $menuObj->where('id',$param['id'])->find();
        $res1 = $menuObj::where('parentId', 'like', '%' . $param['id'] . '%')->delete(); //批量删除
        $res2 = $menuObj::where('id', '=', $param['id'])->delete();
        return show(config('status.success'), '删除成功', 200);
    }
}
