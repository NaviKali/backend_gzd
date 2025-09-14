<?php
// +----------------------------------------------------------------------
// | Code返回参数设置
// +----------------------------------------------------------------------

namespace config\code;

use app\admin\controller\Tool;
use think\response\Json;

class code
{
     const Request_Success = 0;
     const Request_Error = 500;
     const Request_Wrong = 404;
     /**
      * Message->Code返回参数
      * @access public
      * @author L
      * @param int $code 状态代码code->必填
      * @param string $msg 返回内容msg->必填
      * @param array $data 返回数据data->必填
      * TODO 多样式返回Message接口
      * @return Json
      */
     public static function Code(int $code, string $msg, ?array $data): Json
     {
          //*当前时间
          $Date = "20" . Tool::GetNewDate("New");

          //*获取总数
          $Count = count($data);

          
          $_code =
               [
                    //*0->申请成功
                    0 => json(["code" => $code, "static" => "申请成功！", "date" => $Date, "count" => $Count]),
                    //*200->请求成功
                    200 => json(["code" => $code, "msg" => $msg, "data" => $data, "static" => "请求成功！", "date" => $Date, "count" => $Count]),
                    //*404->请求超时
                    404 => json(["code" => $code, "msg" => $msg, "static" => "请求超时！", "date" => $Date, "count" => $Count]),
                    //*500->请求失败
                    500 => json(["code" => $code, "static" => "请求失败！", "date" => $Date, "count" => $Count]),
                    //*400->文件上传失败
                    400 => json(["code" => $code, "msg" => $msg, 'static' => '文件上传失败！', "date" => $Date, "count" => $Count]),
                    //*401->添加失败
                    401 => json(["code" => $code, "msg" => $msg, "static" => "添加失败！", "date" => $Date, "count" => $Count]),
                    //*402->编辑失败
                    402 => json(["code" => $code, "msg" => $msg, "static" => "编辑失败！", "date" => $Date, "count" => $Count]),
                    //*403->删除失败
                    403 => json(["code" => $code, "msg" => $msg, "static" => "删除失败！", "date" => $Date, "count" => $Count]),
                    //*444->无法找到
                    444 => json(["code" => $code, "static" => "无法找到！", "date" => $Date, "count" => $Count]),

               ];
          return $_code[$code];
     }
}