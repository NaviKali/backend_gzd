<?php

namespace app\admin\model\User;

use think\Model;
use app\admin\model\User\User as UserModel;
use think\model\concern\SoftDelete;

class UserRole extends Model
{

      use SoftDelete;
      protected $name = "user_role";//表
      protected $pk = "user_role_guid";//键
      protected $schema = [
            'user_role_id' => "int",
            'user_role_guid' => "varchar",
            'user_role_name' => "varchar",
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

      /**
       * 获取绑定对应用户职务的用户Guid
       * 
       * @access public
       * @static
       * @param string $roleGuid 职务Guid
       * @return array(string[])
       */
      public static function getBindUserListGuid(string $roleGuid): array
      {
            return self::getBindUserList($roleGuid);
      }

      /**
       * 获取绑定对应用户职务的用户姓名
       * 
       * @access public
       * @static
       * @param string $roleGuid 职务Guid
       * @return array(string[])
       */
      public static function getBindUserListName(string $roleGuid): array
      {
            return self::getBindUserList($roleGuid, "user_name");
      }

      /**
       * 获取绑定对应用户职务的用户列表
       * 
       * @access public
       * @static
       * @param string $roleGuid 职务Guid
       * @param string $field 字段列 defult:`user_guid`
       * @return array(string[])
       */
      public static function getBindUserList(string $roleGuid, string $field = "user_guid"): array
      {
            return UserModel::where("user_role_guid", $roleGuid)->column($field);
      }

      /**
       * 当删除用户职务的时候，处理职务下的所有用户改为null
       * 
       * @access public
       * @static
       * @return void
       */
      public static function userRemoveRole(string $roleGuid):void{
            (new UserModel())->update([
                  "user_role_guid"=>""
            ],[
                  "user_role_guid"=>$roleGuid
            ]);
      }
}