<?php

namespace app\admin\verification\Login;


class Login
{
    /**
     * 添加时候
     */
    public static array $Add = [
        "login_account" => "用户账号",
        "login_password" => "用户密码",
        "login_type_guid" => "账号类型Guid",
        "user_name" => "用户名字",
        "user_sex" => "用户性别",
        "user_phone"=>"手机号",
        // "user_role_guid" => "用户职位Guid",
        // "user_status_guid" => "用户状态Guid"
    ];
    /**
     * 登录时
     */
    public static array $Login = [
        "account" => "用户账号",
        "password" => "用户密码",
        "vcode"=>"验证码"
    ];
}