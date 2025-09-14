<?php

namespace app\admin\controller;

use app\Base;
use app\Request;
use app\admin\model\Menu as MenuModel;
use app\admin\verification\Menu as MenuVerification;

class Menu extends Base
{
    /**
     * 删除菜单
     * @access public
     * @api Menu/DeleteMenu
     * @param Request $request
     * @see @param app\admin\verification\Menu[$Delete]
     * @author liulei
     * @version 1.0.0
     * @return \think\Response\Json
     */
    public function DeleteMenu(Request $request): \think\Response\Json
    {
        $params = $request->param();

        $validate = $this->ValidateParams($params, MenuVerification::$Delete);
        if (!is_bool($validate))
            return $validate;

        foreach ($params["menu_guid"] as $k => $v) {
            $find = (new MenuModel())->where("menu_guid", $v)->find();
            if (!$find)
                return $this->ApiError("没有找到对应数据!");
            $find->delete();
        }
        return $this->Success("删除成功!");
    }
    /**
     * 编辑菜单
     * @access public
     * @api Menu/EditMenu
     * @param Request $request
     * @see @param app\admin\verification\Menu[$Edit]
     * @author liulei
     * @version 1.0.0
     * @return \think\Response\Json
     */
    public function EditMenu(Request $request): \think\Response\Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, MenuVerification::$Edit);
        if (!is_bool($validate))
            return $validate;

        $model = (new MenuModel());
        $find = $model->where($model->getPk(), $params[$model->getPk()])->find();
        if (!$find)
            return $this->ApiError("没有找到对应数据!");

        $find->save([
            "menu_name" => $params["menu_name"],
            "menu_to" => $params["menu_to"],
            "menu_icon" => $params["menu_icon"],
            "menu_father_guid" => $params["menu_father_guid"] ?? null,
        ]);

        return $this->Success("编辑成功!");
    }
    /**
     * 添加菜单
     * @access public
     * @api Menu/CreateMenu
     * @param Request $request
     * @see @param app\admin\verification\Menu[$Add]
     * @author liulei
     * @version 1.0.0
     * @return \think\Response\Json
     */
    public function CreateMenu(Request $request): \think\Response\Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, MenuVerification::$Add);
        if (!is_bool($validate))
            return $validate;

        //?判断是否传入父级菜单Guid | 判断是否创建子菜单
        if (!isset($params["menu_father_guid"]))
            $params["menu_father_guid"] = null;

        $find = (new MenuModel())->where("menu_name", $params["menu_name"])->find();
        if ($find)
            return $this->ApiError("菜单已存在!");

        (new MenuModel)->create($params);
        return $this->Success("添加成功!");

    }
    /**
     * 获取菜单列表 | 树
     * @access public
     * @api Menu/getMenuTree
     * @param Request $request look down
     * @author liulei
     * @version 1.0.0
     * @return \think\Response\Json
     */
    public function getMenuTree(Request $request): \think\Response\Json
    {
        /**
         * 可选参数
         * @param string $menu_name 菜单名称
         */

        $params = $request->param();

        $con = [];

        if(!empty($params["menu_name"]))
            $con[] = ["menu_name","like","%".$params["menu_name"]."%"];

        if (!empty($params["open"])) {
            if ($params["open"] == MenuModel::OPEN_ALL) {
            } else if ($params["open"] == MenuModel::OPEN_GETSONLEVEL) {
                $con[] = [
                    "menu_father_guid",
                    '=',
                    null
                ];
            }
        } else {
            $con[] = [
                "menu_father_guid",
                '=',
                null
            ];
        }

        $menulist = (new MenuModel)
            ->where($con)
            ->field([
                'menu_id' => 'key',
                'menu_guid',
                'menu_name',
                'menu_to',
                'menu_icon',
                'menu_father_guid'
            ])
            ->order('create_datetime', 'asc')
            ->select()
            ->toArray();

        $this->getChildren($menulist);

        return $this->Success("获取菜单树成功!", $menulist);
    }

    /**
     * 获取菜单跳转列表
     * @access public
     * @api Menu/getMenuToList
     * @param Request $request hasn't params
     * @author liulei
     * @version 1.0.0
     * @return \think\Response\Json
     */
    public function getMenuToList(Request $request): \think\Response\Json
    {
        return $this->Success("获取菜单树成功!", (new MenuModel())->column("menu_to"));
    }

    /**
     * 获取子菜单
     * 
     * @access public
     * @param array $arr
     * @return void
     */
    public function getChildren(array &$arr): void
    {
        foreach ($arr as $k => &$v) {
            $v["children"] = (new MenuModel())
                ->whereNotNull("menu_father_guid")
                ->where("menu_father_guid", $v["menu_guid"])
                ->field([
                    'menu_id' => 'key',
                    'menu_guid',
                    'menu_name',
                    'menu_to',
                    'menu_icon',
                    'menu_father_guid'
                ])
                ->order('create_datetime', 'asc')
                ->select()
                ->toArray() ?? [];
            if ($v["children"] != []) {
                $this->getChildren($v["children"]);
            }
        }
    }

}