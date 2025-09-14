<?php
/**
 * title_headers
 * 生成代码-L
 */
namespace app\api\controller\Menu;

use app\BaseController;
use app\admin\controller\Tool as Tool;
use app\model\Menu\Menu as ModelMenu;
use app\Request;
use think\console\command\make\Model;

class Menu extends BaseController
{

     /**
      * 获取title_headers列表
      * @author L
      */
     public function getMenuList()
     {
          $MenuList = ModelMenu::
               field([
                    'demo_order_id',
                    'demo_order_guid',
                    'demo_order_create_time',
                    back_font
               ])
               ->order('demo_order_create_time', 'desc')
               ->select()
               ->toArray();
          return Tool::msg(200, "获取成功!", $MenuList);
     }
     /**
      * 获取title_headers详情
      * @author L
      */
     public function getMenuItem(Request $request)
     {
          $params = $request->param();
          $this->validate($params, [
               "demo_order_id" => 'require',
          ]);
          $get = ModelMenu::where("demo_order_id", $params["demo_order_id"])
               ->field([
                    'demo_order_id',
                    'demo_order_guid',
                    'demo_order_create_time',
                    back_font
               ])->find();
          if (!$get)
               return Tool::msg(404, "获取失败!", [], "查找不到数据。");
          return Tool::msg(200, "获取成功!", $get->toArray());
     }


}