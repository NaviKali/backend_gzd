<?php

namespace app\admin\verification\User;



class UserRole {
    /**
     * 添加时
     */
    public static array $Add = [
        "user_role_name"=>"用户职位名称",
        "user_guid"=>"所属用户绑定guid"
    ];  

    public static array $Delete = [
        "user_role_guid"=>"用户职位guid"
    ];

    public static array $Edit = [
        "user_role_guid"=>"用户职位guid",
        "user_role_name"=>"用户职位名称",
    ];

}