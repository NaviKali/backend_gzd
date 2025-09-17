<?php

namespace app\admin\controller\StudentKeepscore;

use app\Base;
use app\Request;
use think\response\Json;
use app\admin\verification\StudentKeepscore\StudentKeepscore as verifictaionStudentKeepscore;
use app\admin\model\StudentKeepscore\StudentKeepscore as ModelStudentKeepscore;
use app\admin\model\StudentKeepscore\StudentKeepscoreType\StudentKeepscoreType as ModelStudentKeepscoreType;
use app\admin\model\Student\Student as ModelStudent;


class StudentKeepscore extends Base
{

    public ModelStudentKeepscore $model;

    public function __construct()
    {
        $this->model = (new ModelStudentKeepscore);
    }

    /**
     * 添加学生记分
     * 
     * @access public
     * @api StudentKeepscore.StudentKeepscore/deleteStudentKeepscore
     * @param Request $request
     * @return Json
     */
    public function deleteStudentKeepscore(Request $request): Json
    {
        $params = $request->param();

        $validate = $this->ValidateParams($params, verifictaionStudentKeepscore::$Delete);
        if (!$validate)
            return $validate;

        foreach ($params["student_keepscore_guid"] as $k => $v) {
            $find = $this->model->where('student_keepscore_guid', $v)->find();
            if (!$find)
                return $this->ApiError("该记分不存在!");

            $num = (new ModelStudentKeepscoreType())->where('student_keepscore_type_guid', $find['student_keepscore_type_guid'])->value('keepscore_num');

            (new ModelStudent())->where('student_guid', $find['student_guid'])
                ->find()
                ->dec('student_score_count', $num)
                ->save();
            $find->delete();
        }


        return $this->Success("删除成功!");
    }
    /**
     * 添加学生记分
     * 
     * @access public
     * @api StudentKeepscore.StudentKeepscore/addStudentKeepscore
     * @param Request $request
     * @return Json
     */
    public function addStudentKeepscore(Request $request): Json
    {
        $params = $request->param();

        $validate = $this->ValidateParams($params, verifictaionStudentKeepscore::$Add);
        if (!$validate)
            return $validate;

        for ($i = 0; $i < $params["count"]; $i++) {
            $this->model->create([
                "student_guid" => (new ModelStudent())->where('student_number', $params["student_number"])->value("student_guid"),
                "student_keepscore_type_guid" => $params["student_keepscore_type_guid"],
                "student_keepscore_date" => $params["student_keepscore_date"]
            ]);
        }

        $score = $params["count"] * (new ModelStudentKeepscoreType)->where('student_keepscore_type_guid', $params['student_keepscore_type_guid'])->value("keepscore_num");
        (new ModelStudent())->handleStudentScoreCount($params["student_number"], $score);

        return $this->Success("添加成功!");
    }
    /**
     * 获取学生记分列表
     * 
     * @access public
     * @api StudentKeepscore.StudentKeepscore/getStudentKeepscoreList
     * @param Request $request
     * @return Json
     */
    public function getStudentKeepscoreList(Request $request): Json
    {

        $params = $request->param();

        $con = [];

        if (!empty($params["student_guid"]) and is_string($params["student_guid"]))
            $con['student_keepscore.student_guid'] = $params['student_guid'];


        $query = $this->model->where($con)
            ->join("student", 'student_keepscore.student_guid = student.student_guid', "left")
            ->join("student_keepscore_type", "student_keepscore.student_keepscore_type_guid = student_keepscore_type.student_keepscore_type_guid", "left")
            ->field([
                'student_keepscore_id' => "key",
                'student_name',
                'student_keepscore_type_name',
                'keepscore_num',
                'student_keepscore_guid',
                'student_keepscore_date',
            ]);

        $count = $query->count();

        $list = self::PagePacka($query, $request->param())
            ->order('student_keepscore_date', "desc")
            ->select();


        return $this->Success("获取成功!", ["list" => $list, "count" => $count]);

    }

}