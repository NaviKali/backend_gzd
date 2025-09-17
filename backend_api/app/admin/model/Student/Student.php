<?php
namespace app\admin\model\Student;

use app\Base;
use app\DictionaryMap;
use think\Model;
use think\model\concern\SoftDelete;
use app\admin\model\StudentKeepscore\StudentKeepscore as ModelStudentKeepscore;

class Student extends Model
{
      use SoftDelete;
      protected $name = "student";//表
      protected $pk = "student_guid";//键
      protected $schema = [
            'student_id' => "int",
            'student_guid' => "varchar",
            'student_number' => "varchar",
            'student_name' => "varchar",
            'student_sex' => "int",
            'student_score_count' => 'int',
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
       * 设置性别
       * 
       * @param mixed $value
       * @param mixed $data
       * @return array ["value"=>?,"text"=>?]
       */
      public function getStudentSexAttr($value): array
      {
            return ["value" => $value, "text" => DictionaryMap::getAdminDictionaryMap()["student"]["student_sex"][$value]];
      }

      /**
       * 处理学生记分
       * 
       * @access public
       * @return void
       */
      public function handleStudentScoreCount(string $studentNumber, int $score): void
      {
            $find = $this->where('student_number', $studentNumber)->find();
            if (!$find)
                   (new Base())->ApiError("没有找到对应学生!");

            $find->inc("student_score_count", $score)->save();
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
      public static function onBeforeInsert(mixed $data): void
      {
            self::handleAddGuid($data);
      }
}