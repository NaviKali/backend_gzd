<?php

namespace app\admin\controller\User;

use app\Base;
use app\Request;
use app\admin\model\User\User as UserModel;
use app\admin\verification\User\User as UserVerification;
use app\void\User as UserVoid;
use tank\Tool\Tool;
use tank\Upload\Upload;
use think\facade\Filesystem;

class User extends Base
{
      public array $initFiberArr = ['\app\admin\controller\User\UserConfig' => 'getUserConfig'];

      /**
       * 获取下拉菜单用户列表
       * 
       * @access public
       * @api User.User/getSelectUserList
       * @param \app\Request $request
       * @version 1.0.0
       * @return \think\Response\Json
       */
      public function getSelectUserList(Request $request):\think\Response\Json
      {
            /**
             * ?可选参数
             * 
             * @param string user_name 用户姓名
             */

            $params = $request->param();

            $con = [];
            if(!empty($params["user_name"]) and is_string($params["user_name"]) )
                  $con[] = ["user_name", "like", $params["user_name"] . "%"];

            $list = (new UserModel())
            ->where($con)
            ->order("create_datetime","asc")
            ->column([
                  "user_name",
                  "user_guid"
            ]);

            return $this->Success("获取成功!",$list);
      }

      /**
       * 获取用户列表
       * 
       * @access public
       * @api User.User/getUserList
       * @author liulei
       * @param \app\Request $request
       * @version 1.0.0
       * @return \think\Response\Json|array
       */
      public function getUserList(Request $request): \think\Response\Json
      {

            /**
             * 可选参数
             * @param string user_name 用户姓名
             * @param int user_sex 用户性别
             * @param string $user_phone 用户手机号
             */
            
            $params = $request->param();

            $con = [];
            if (!empty($params["user_name"]) and is_string($params["user_name"]))
                  $con[] = ["user_name", "like", $params["user_name"] . "%"];
            if(!empty($params["user_sex"]) and is_int($params["user_sex"]))
                  $con[] = ["user_sex","=",$params["user_sex"]];
            if(!empty($params["user_phone"]) and is_string($params["user_phone"]))
                  $con[] = ["user_phone","like",$params["user_phone"] ."%"];

            $query = (new UserModel())
            ->join("user_role","user.user_role_guid = user_role.user_role_guid","left")
            ->join("user_status","user.user_status_guid = user_status.user_status_guid","left")
            ->where($con);

            $count = $query->count();

            $list = self::PagePacka($query, $params)->field([
                  "user_guid",
                  "user_name",
                  "user_sex",
                  "user_role.user_role_name",
                  "user_image",
                  "user_phone",
                  "user_email",
                  "user.user_status_guid",
                  "user_information",
                  "user.create_datetime"
            ])->select();


            return $this->Success("获取成功!", ["list" => $list, "count" => $count]);
      }
      /**
       * 新建用户
       * 
       * @access public
       * @api User.User/CreateUser
       * @author liulei
       * @param \app\Request $request
       * @version 1.0.0
       * @return \think\Response\Json|array
       */
      public function CreateUser(Request $request)
      {
            $params = $request->param();
            $validate = $this->ValidateParams($params, UserVerification::$Add);
            if (!is_bool($validate))
                  return $validate;
            $params = $this->BindGuid('user_guid', $params);
            UserModel::create($params);
            return $this->Success('添加成功!');
      }
      /**
       * 获取当前用户信息
       * 
       * @access public
       * @api User.User/getCurrentUserInformation
       * @author liulei
       * @param \app\Request $request
       * @version 1.0.0
       * @return \think\Response\Json|array
       */
      public function getCurrentUserInformation(): \think\Response\Json|array
      {
            $fiber = $this->initFiber($this->initFiberArr);

            $userInfo = (new UserVoid)->getCurrentUserInformation();

            foreach ($fiber as $k => $v) {
                  $userInfo = array_merge($userInfo, $v);
            }

            return $this->Success("当前用户信息获取成功！", $userInfo);
      }
      /**
       * 上传头像
       * @access public
       * @api User.User/uploadsImage
       * @author liulei
       * @param \app\Request $request
       * @version 1.0.0
       * @return \think\Response\Json
       */
      public function uploadsImage(Request $request): \think\Response\Json
      {
            $params = $request->param();

            //*删除头像
            if (!empty($params["filename"])) {
                  unlink(public_path("upload") . $params["filename"]);
                  return $this->Success("删除成功!");
            }
            $file = $request->file("file");
            $name = Filesystem::disk("public")->putFile("user", $file);
            //*修改头像
            $find = (new UserModel())->where("user_guid", (new UserVoid())->UserGuid)->find();
            if ($find) {
                  $find->save([
                        "user_image" => $name
                  ]);
            }

            return $this->Success("上传成功!", [
                  "fileName" => $name,
            ]);
      }

}