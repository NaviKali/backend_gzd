<?php
namespace app\admin\model\StudentKeepscore\StudentKeepscoreType;

use app\DictionaryMap;
use think\Model;
use think\model\concern\SoftDelete;

class StudentKeepscoreType extends Model
{
    use SoftDelete;
    protected $name = "student_keepscore_type";//表
    protected $pk = "student_keepscore_type_guid";//键
    protected $schema = [
        'student_keepscore_type_id' => "int",
        'student_keepscore_type_guid' => "varchar",
        'student_keepscore_type_name' => "varchar",
        'keepscore_num'=>"int",
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