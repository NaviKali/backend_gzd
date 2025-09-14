<?php

namespace app\admin\controller\Login;

use app\Base;
use app\Request;
use app\admin\verification\Login\Login as LoginVerification;
use app\admin\model\Login\Login as LoginModel;
use app\admin\model\User\User as UserModel;
use app\admin\model\User\UserStatus as UserStatusModel;
use app\Tool;
use app\DictionaryMap;
use think\captcha\facade\Captcha;
use think\Response;

class Login extends Base
{
    /**
     * 获取验证码
     * 
     * @access public
     * @author victus
     * @api Login.Login/getVerCode
     * @param \app\Request $request
     * @return array|Response
     */
    public function getVerCode(Request $request): array|Response
    {
        return Captcha::create();
    }

    /**
     * 账号密码登录
     * TODO管理员帐号密码登录
     * !输入的错误过多会导致禁止登录
     */
    public function AccountLogin(Request $request): \think\response\Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, LoginVerification::$Login);
        if (!is_bool($validate))
            return $validate;

        //TODO验证码验证
        if(!captcha_check($params["vcode"]))
            return $this->ApiError("验证码错误!");

        //TODO密码格式验证
        $PassWordVerification = Tool::ForcePassWordVerification($params['password']);
        if (!is_bool($PassWordVerification))
            return $PassWordVerification;

        //*查找是否存在当前用户
        $con = [
            "login_account" => $params["account"],
            "login_password" => $params["password"]
        ];

        $findCurrentUser = (new LoginModel)->where($con)->field([
            'login_status',
            'user_guid',
        ])->find();

        if (!$findCurrentUser)
            return $this->ApiError('当前用户不存在!');

        $findCurrentUser = $findCurrentUser->toArray();

        //?判断当前用户是否处于禁止登录状态
        if ($findCurrentUser["login_status"] == LoginModel::LOGIN_STATUS_STOP)
            return $this->Warning(LoginModel::LOGIN_STATUS_STOP_TEXT);

        //*状态Text
        $findCurrentUser["login_status_text"] = (new LoginModel())->getDictionaryMap()[$findCurrentUser["login_status"]];

        $data = LoginModel::getToken($findCurrentUser);
        return $this->Success('登录成功!', $data);
    }
    /**
     * [用户状态默认为允许，0->允许,1->禁止]
     * 添加后台系统帐号
     */
    public function createAccountLogin(Request $request): \think\response\Json
    {
        $params = $request->param();
        $params['login_status'] = LoginModel::LOGIN_STATUS_OK;
        $validate = $this->ValidateParams($params, LoginVerification::$Add);
        if (!is_bool($validate))
            return $validate;

        //?是否账号存在
        $find = (new LoginModel())->where([
            "login_account" => $params["login_account"]
        ])->find();
        if ($find)
            return $this->ApiError("账号已存在!");

        $params = $this->BindGuid("login_guid", $params);
        //*创建用户
        $userData = (new UserModel)->create([
            "user_guid" => $this->BindGuid("user_guid", date("Y-m-d H:i:s")),
            "user_name" => $params["user_name"],
            "user_sex" => $params["user_sex"],
            "user_role_guid" => $params["user_role_guid"] ?? "",
            "user_image" => $params["user_image"] ?? "",
            "user_phone" => $params["user_phone"],
            "user_email" => $params["user_email"] ?? "",
            "user_status_guid" => $params["user_status_guid"] ?? (new UserStatusModel)->where([
                "user_status_name" => UserStatusModel::USER_STATUS_ONLINE_TEXT
            ])->value("user_status_guid"),
            "user_information" => $params["user_information"] ?? ""
        ]);

        $params["user_guid"] = $userData["user_guid"];
        LoginModel::create($params);
        return $this->Success("添加成功!");
    }



}