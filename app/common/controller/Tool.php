<?php

namespace app\common\controller;

class Tool
{
    public function tree0($arr, $parentId)
    {
        $tree = [];
        foreach ($arr as $key => $val) {
            if ($val['parentId'] == $parentId) {
                if (!empty($this->tree($arr, $val['id']))) {
                    $val['children'] = $this->tree0($arr, $val['id']);
                }
                $tree[] = $val;
            }
        }
        return $tree;
    }

    public function tree($arr, $parentId='')
    {
        $tree = [];
        foreach ($arr as $key => $val) {
            if ($val['parentId'] == $parentId) {
                if($val['parentId']==''){
                    $parentIdList = (string)$val['id'];
                    $val['parentId'] = [null];
                }else{
                    $parentIdList = $val['parentId'].','.$val['id'];
                    
                }
                if ($this->tree($arr, $parentIdList)) {
                    $val['children'] = $this->tree($arr, $parentIdList);
                }
                $tree[] = $val;
            }
        }
        return $tree;
    }

    public function editTree($arr, $pid, $id)
    {
        $tree = [];
        foreach ($arr as $key => $val) {
            if ($val['pid'] == $pid) {
                if ($val['id'] == $id) {
                } else {
                    if (!empty($this->editTree($arr, $val['id'], $id))) {
                        $val['children'] = $this->tree($arr, $val['id']);
                    }
                    $tree[] = $val;
                }
                //$tree = \array_merge($tree,$this->tree($arr, $val['id']));
            }
        }
        return $tree;
    }
}
