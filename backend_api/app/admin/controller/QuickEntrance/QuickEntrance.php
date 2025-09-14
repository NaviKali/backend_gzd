<?php

namespace app\admin\controller\QuickEntrance;

use app\Base;
use app\Request;
use app\admin\verification\QuickEntrance\QuickEntrance as ValidateQuickEntrance;
use app\admin\model\QuickEntrance\QuickEntrance as ModelQuickEntrance;


class QuickEntrance extends Base
{
    /**
     * 添加快捷入口
     * @access public
     * @api QuickEntrance.QuickEntrance/addQuickEntrance
     * @author liulei
     * @version 1.0.0
     * @param \app\Request $request
     * @return \think\response\Json
     */
    public function addQuickEntrance(Request $request): \think\response\Json
    {

        $params = $request->param();

        $validate = $this->ValidateParams($params, ValidateQuickEntrance::$Add);
        if (!is_bool($validate))
            return $validate;

        $find = (new ModelQuickEntrance())->where([
            "menu_guid" => $params["menu_guid"]
        ])->find();
        if ($find)
            return $this->Warning("快捷入口已存在");

        (new ModelQuickEntrance())->create([
            "menu_guid" => $params["menu_guid"]
        ]);

        return $this->Success("添加成功!", []);
    }
    /**
     * 获取快捷入口列表
     * @access public
     * @api QuickEntrance.QuickEntrance/getQuickEntrance
     * @author liulei
     * @version 1.0.0
     * @param \app\Request $request
     * @return \think\response\Json
     */
    public function getQuickEntrance(Request $request): \think\response\Json
    {
        $con = [];

        $query = (new ModelQuickEntrance())
            ->join("menu", "quick_entrance.menu_guid = menu.menu_guid", "left")
            ->where($con);

        $count = $query->count();
        $list = self::PagePacka($query, $request->param())
            ->field([
                "quick_entrance_guid",
                "menu_name",
                "menu_to",
                "menu_icon"
            ])
            ->select()
            ->toArray();

        return $this->Success("获取成功!", ["list" => $list, "count" => $count]);
    }
    /**
     * 删除快捷入口列表
     * @access public
     * @api QuickEntrance.QuickEntrance/deleteQuickEntrance
     * @author liulei
     * @version 1.0.0
     * @param \app\Request $request
     * @return \think\response\Json
     */
    public function deleteQuickEntrance(Request $request): \think\response\Json
    {
        $params = $request->param();

        $validate = $this->ValidateParams($params, ValidateQuickEntrance::$Delete);
        if (!is_bool($validate))
            return $validate;

        $find = (new ModelQuickEntrance())->where([
            "quick_entrance_guid" => $params['quick_entrance_guid']
        ])->find();
        if (!$find)
            return $this->Error('没有找到对应快捷入口');

        $find->force(true)->delete();

        return $this->Success('删除成功!');

    }
}