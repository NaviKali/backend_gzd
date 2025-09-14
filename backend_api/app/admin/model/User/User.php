<?php
namespace app\admin\model\User;

use app\DictionaryMap;
use think\Model;
use think\model\concern\SoftDelete;

class User extends Model
{
      use SoftDelete;
      protected $name = "user";//表
      protected $pk = "user_id";//键
      protected $schema = [
            'user_id' => "int",
            'user_guid' => "varchar",
            'user_name' => "varchar",
            'user_sex' => "varchar",
            'user_role_guid' => "varchar",
            'user_image' => "varchar",
            'user_phone' => "varchar",
            'user_email' => "varchar",
            "user_status_guid" => "varchar",
            'user_information' => "varchar",
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

      /**
       * 设置用户性别
       * 
       * @param mixed $value
       * @param mixed $data
       * @return array ["value"=>?,"text"=>?]
       */
      public function getUserSexAttr($value): array
      {
            return ["value"=>$value,"text"=>DictionaryMap::getAdminDictionaryMap()["user"]["user_sex"][$value]] ;
      }


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
}