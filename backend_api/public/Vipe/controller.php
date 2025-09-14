<?php
/**
 * title_headers
 * 生成代码-L
 */
namespace app\admin\controller\Menu;
use app\BaseController;
use app\admin\controller\Tool as Tool;
use app\model\Menu\Menu as ModelMenu;
use app\Request;
use think\console\command\make\Model;
use config\upload as upload;



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
               'demo_order_guid',
               'demo_order_create_time',

               back_font
          ])
          ->order('demo_order_create_time','desc')   
          ->select()
          ->toArray();
          return Tool::msg(200,"获取成功!",$MenuList);
     }
     /**
      * 搜索title_headers
      * @author L
      */
      public function SearchMenu(Request $request)
      {
          $params = $request->param();
          $this->validate($params,[
             
          ]);
          $model = ModelMenu::class;
          $data =[
               Search_con
          ];
     
          $Search = Tool::GetQueryWhere($model,$data,['demo_order_create_time','desc']);
          return Tool::msg(200,"获取成功!",$Search);
      }
     /**
      * 批量删除title_headers
      * @author L
      */
      public function BatchDeleteMenu(Request $request)
      {
          $params = $request->param();
          $this->validate($params, [
               "select_Menu|select_Menu" => "require",
          ]);
          foreach ($params['select_Menu'] as $value) {
             $end = ModelMenu::where("demo_order_guid", $value['demo_order_guid'])->select()->delete();
          }
          return Tool::msg(200,"删除成功!",[],"批量删除想要删除的对象!");
      }
      /**
      * 获取title_headers历史记录->(软删除)
      * @author  L
      */
      public function getTrasheMenu()
      {
          $getTrashe = ModelMenu::
          field([
               'demo_order_guid',
               'demo_order_delete_time',

               back_font
          ])->onlyTrashed()
          ->order('demo_order_create_time','desc')   
          ->select()->toArray();
     return Tool::msg(200,"获取成功!",$getTrashe);

     
     }
      /**
      * 恢复title_headers历史记录
      * @author L
      */
      public function RestoreTrasheMenu(Request $request)
      {
          $params = $request->param();
          $this->validate($params, [
               "demo_order_guid|demo_order_guid" => "require",
          ]);
          $Restore = ModelMenu::where('demo_order_guid',$params['demo_order_guid'])->onlyTrashed()->find()->restore();
          return Tool::msg(200,"恢复成功!");

      }
       /**
       * 彻底删除title_headers历史记录->(彻底删除)
       * @author  L
       */
      public function ForceTrasheMenu(Request $request)
      {
          $params = $request->param();
          $this->validate($params, [
               "demo_order_guid|demo_order_guid" => "require",
          ]);
          $Restore = ModelMenu::where('demo_order_guid',$params['demo_order_guid'])->onlyTrashed()->find()->force()->delete();
          return Tool::msg(200,"删除成功!");
      }

     test




}