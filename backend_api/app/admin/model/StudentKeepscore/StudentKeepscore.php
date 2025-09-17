<?php
namespace app\admin\model\StudentKeepscore;

use think\Model;
use app\admin\model\StudentKeepscore\StudentKeepscoreType\StudentKeepscoreType as ModelStudentKeepscoreType;
use app\admin\model\Student\Student as ModelStudent;

class StudentKeepscore extends Model
{
    protected $name = "student_keepscore";//表
    protected $pk = "student_keepscore_guid";//键
    protected $schema = [
        'student_keepscore_id' => "int",
        'student_keepscore_guid' => "varchar",
        'student_guid' => "varchar",
        'student_keepscore_type_guid' => "varchar",
        'student_keepscore_date' => "date",
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

    /**
     * 处理删除数据
     * 
     * @access public
     * @param string $typeGuid 记分类型Guid must
     * @return void
     */
    public function handleDeleteData(string $typeGuid): void
    {
        $this->where([
            "student_keepscore_type_guid" => $typeGuid,
        ])->select()->filter(function (object $item) use ($typeGuid): void {
            //*获取类型记分分数
            $num = (new ModelStudentKeepscoreType)->where('student_keepscore_type_guid', $typeGuid)->value('keepscore_num');
            //*student表减去对应记分类型所获得的所有分数
            (new ModelStudent)->where('student_guid', $item["student_guid"])
                ->dec('student_score_count', $num)
                ->save();
            $item->delete();
        });
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