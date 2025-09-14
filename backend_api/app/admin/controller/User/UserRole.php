<?php

namespace app\admin\controller\User;

use app\Base;
use app\admin\model\User\UserRole as UserRoleModel;
use app\Request;
use app\admin\verification\User\UserRole as UserVerificationModel;
use app\admin\model\User\User as UserModel;
use app\Tool;

class UserRole extends Base
{
    /**
     * 编辑用户职务
     * 
     * @access public
     * @api User.UserRole/editUserRole
     * @author victus
     * @version 1.0.0
     * @param \app\Request $request
     * @return \think\Response\Json
     */
    public function editUserRole(Request $request): \think\Response\Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, UserVerificationModel::$Edit);
        if (!is_bool($validate))
            return $validate;

        $find = (new UserRoleModel())->where("user_role_guid", $params["user_role_guid"])->find();
        if (!$find)
            return $this->ApiError("没有找到对应数据!");

        $find->save($params);


        //todo 所有人在对应职务清除，其次重新修改绑定职务
        (new UserModel())->update([
            "user_role_guid" => ""
        ], [
            "user_role_guid" => $params["user_role_guid"]
        ]);

        //todo设置职务绑定用户
        if (!empty($params["bind_user_list"])) {
            foreach ($params["bind_user_list"] as $k => $v) {
                $find = (new UserModel())->where("user_guid", $v)->find();
                if (!$find)
                    return $this->ApiError("没有找到对应数据!");
                $find->save([
                    "user_role_guid" => $params["user_role_guid"]
                ]);
            }
        }

        return $this->Success("编辑成功!");
    }
    /**
     * 删除用户职务
     * 
     * @access public
     * @api User.UserRole/deleteUserRole
     * @param \app\Request $request
     * @author victus
     * @version 1.0.0
     * @return \think\Response\Json
     */
    public function deleteUserRole(Request $request): \think\Response\Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, UserVerificationModel::$Delete);
        if (!is_bool($validate))
            return $validate;

        foreach ($params["user_role_guid"] as $k => $v) {
            UserRoleModel::userRemoveRole($v);

            $find = (new UserRoleModel())->where("user_role_guid", $v)->find();
            if (!$find)
                return $this->ApiError("没有找到对应数据!");
            $find->delete();
        }
        return $this->Success("删除成功!");
    }

    /**
     * 获取用户职务列表
     * 
     * @access public
     * @api User.UserRole/getUserRoleList
     * @param \app\Request $request
     * @author victus
     * @version 1.0.0
     * @return \think\Response\Json
     */
    public function getUserRoleList(Request $request): \think\Response\Json
    {
        /**
         * 可选参数
         * @param string $user_role_name 用户职务名称
         */

        $params = $request->param();

        $con = [];

        if (!empty($params["user_role_name"]))
            $con[] = ["user_role_name", "like", "%" . $params["user_role_name"] . "%"];

        $query = (new UserRoleModel())->where($con);

        $count = $query->count();

        $list = self::PagePacka($query, $params)
            ->field([
                "user_role_id" => "key",
                "user_role_guid",
                "user_role_name",
                "create_datetime",
            ])
            ->order('create_datetime', "desc")
            ->select()
            ->filter(function (object $v): object {
                $v["bind_user_list"] = UserRoleModel::getBindUserListName($v["user_role_guid"]);
                return $v;
            });


        return $this->Success('获取成功!', ["list" => $list, "count" => $count]);
    }
    /**
     * 添加用户职位
     * 
     * @access public
     * @api User.UserRole/CreateUserRole
     * @author victus
     * @version 1.0.0
     * @return \think\Response\Json
     */
    public function CreateUserRole(Request $request): \think\Response\Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, UserVerificationModel::$Add);
        if (!is_bool($validate))
            return $validate;

        $find = (new UserRoleModel())->where('user_role_name', $params['user_role_name'])->find();
        if ($find)
            return $this->ApiError("该职位名称已存在!");
        $roleData = (new UserRoleModel())->create($params);

        //todo设置职务绑定用户
        if (!empty($params["user_guid"])) {
            foreach ($params["user_guid"] as $k => $v) {
                $find = (new UserModel())->where("user_guid", $v)->find();
                if (!$find)
                    return $this->ApiError("没有找到对应数据!");
                $find->save([
                    "user_role_guid" => $roleData["user_role_guid"]
                ]);
            }
        }


        return $this->Success('添加成功!');
    }

}