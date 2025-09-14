<?php

namespace app\admin\model\User;

use think\Model;
use think\model\concern\SoftDelete;

class UserConfig extends Model
{

    use SoftDelete;
    protected $name = "user_config";//表
    protected $pk = "user_config_guid";//键
    protected $schema = [
        'user_config_id' => "int",
        'user_config_guid' => "varchar",
        'user_guid' => "varchar",
        'user_config_menu_collapsed' => 'int',
        'user_config_notification_position' => "varchar",
        'user_config_footnote' => "varchar",
        "user_config_watermark" => "varchar",
        "user_config_view_model"=>"varchar",
        'create_datetime' => "datetime",
        'update_datetime' => "datetime",
        'delete_datetime' => "datetime",

    ];//定义字段信息

    protected $type = [];//字段转换类型
    protected $disuse = [];//废弃字段
    protected $json = [];//JSON字段
    protected $readonly = [];//只读字段
    protected $jsonAssoc = true;//JSON数据返回数组
    protected $autoWriteTimestamp = 'datetime';
    protected $deleteTime = "delete_datetime";
    public static function onAfterRead()
    {

    }
    public static function onAfterUpdate()
    {

    }
    public static function onAfterWrite()
    {

    }
    public static function onAfterDelete()
    {

    }
    public static function onBeforeInsert(mixed $data): void
    {
        self::handleAddGuid($data);
    }
}