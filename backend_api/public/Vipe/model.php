<?php
/**
 * title_headers\
 * 生成代码-L
 */
namespace app\model\Menu;

use think\Model as ThinkModel;
use think\model\concern\SoftDelete;
use app\admin\controller\Tool as Tool;
class Menu extends ThinkModel
{
     //开启软删除
     use SoftDelete;
     // 删除字段
     protected $deleteTime = 'menu_delete_time';
     // 设置json类型字段
     protected $json = [];
     // 设置主键名
     protected $pk = 'menu_id';
     // 设置废弃字段
     protected $disuse = [];
     // 设置字段信息
     protected $schema = [
          'menu_id' => 'int',
          'menu_guid' => 'string',
          test
          'menu_create_time' => 'datetime',
          'menu_update_time' => 'datetime',
          'menu_delete_time' => 'datetime',
     ];
     // 开启自动写入时间戳字段
     protected $autoWriteTimestamp = 'datetime';
     // 创建时间
     protected $createTime = 'menu_create_time';
     // 修改时间
     protected $updateTime = 'menu_update_time';
     /**
      * 新增前
      */
     public static function onBeforeInsert(self $model): void
     {
     }

     /**
      * 更新前
      */
     public static function onBeforeUpdate(self $model): void
     {
     }

     /**
      * 删除前
      */
     public static function onBeforeDelete(self $model): void
     {
     }
     
 
}
