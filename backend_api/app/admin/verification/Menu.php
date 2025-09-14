<?php
namespace app\admin\verification;

class Menu
{
    /**
     * 添加
     */
    public static array $Add = [
        "menu_name" => "菜单名称",
        "menu_to" => "菜单跳转",
        //* "menu_father_guid" 可选
    ];
    public static array $Edit = [
        "menu_guid" => "菜单Guid",
        "menu_name" => "菜单名称",
        "menu_to" => "菜单跳转",
        "menu_icon" => "菜单图标",
        //* "menu_father_guid" 可选
    ];
    public static array $Delete = [
        "menu_guid" => "菜单Guid",
    ];
}