<?php

namespace app\admin\controller\User;

use app\Base;
use app\Request;
use app\admin\model\User\UserConfig as UserConfigModel;
use app\void\User as UserVoid;
use app\admin\verification\User\UserConfig as ValidateUserConfig;


class UserConfig extends Base
{

    /**
     * 获取用户配置
     * @access public
     * @static
     * @api User.UserConfig/getUserConfig
     * @author liulei
     * @version 1.0.0
     * @return \think\Response\Json|array
     */
    public static function getUserConfig(): array
    {
        $query = (new UserConfigModel())->where('user_guid', (new UserVoid())->getCurrentUserGuid())
        ->field([
            "user_config_guid",
            "user_guid",
            "user_config_menu_collapsed",
            "user_config_notification_position",
            "user_config_footnote",
            "user_config_watermark",
            "user_config_view_model",
        ])->find();
        return $query->toArray();
    }
    /**
     * 设置用户配置
     * @access public
     * @static
     * @api User.UserConfig/setUserConfig
     * @author liulei
     * @version 1.0.0
     * @return \think\Response\Json|array
     */
    public function setUserConfig(Request $request): \think\Response\Json
    {
        $params = $request->param();

        $find = (new UserConfigModel())->where('user_guid', (new UserVoid())->getCurrentUserGuid())->find();
        if (!$find)
            return $this->ApiError("没有找到对应用户!");

        $find->save($params);
        return $this->Success("修改成功!");
    }


}