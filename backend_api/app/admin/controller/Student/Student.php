<?php

namespace app\admin\controller\Student;

use app\Base;
use app\Request;
use app\admin\model\Student\Student as ModelStudent;
use think\response\Json;
use app\admin\verification\Student\Student as VerificationStudent;


class Student extends Base
{

    public ModelStudent $studentModel;


    public function __construct()
    {
        $this->studentModel = (new ModelStudent());
    }


    /**
     * Get Student List
     * 
     * @access public
     * @api Student.Student/getStudentList
     * @date 2025-09-13
     * @param Request $request
     * @return Json
     */
    public function getStudentList(Request $request): Json
    {
        $params = $request->param();

        $con = [];

        if (!empty($params["student_name"]) and is_string($params["student_name"]))
            $con[] = ["student_name", "LIKE", '%' . $params["student_name"] . '%'];
        if (!empty($params["student_sex"]) and is_int($params["student_sex"]))
            $con['student_sex'] = $params["student_sex"];
        if (!empty($params["student_number"]) and is_string($params["student_number"]))
            $con[] = ['student_number', 'LIKE', $params['student_number'] . '%'];

        $query = $this->studentModel->where($con)
            ->field([
                "student_id"=>"key",
                "student_guid",
                "student_number",
                "student_name",
                "student_sex",
                "student_score_count",
                "create_datetime",
            ]);

        $count = $query->count();

        $list = $this->PagePacka($query, $request->param())
            ->order(["student_score_count"=>"desc","student_number"=>"asc"])->select();

        return $this->Success("获取成功!", ["list" => $list, "count" => $count]);
    }

    /**
     * Create Student
     * 
     * @access public
     * @api Student.Student/createStudent
     * @date 2025-09-13
     * @param Request $request
     * @return Json
     */
    public function createStudent(Request $request): Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, VerificationStudent::$Add);
        if (!$validate)
            return $validate;


        $find = $this->studentModel->where("student_number", $params["student_number"])->find();
        if ($find)
            return $this->ApiError("该学号学生已存在!");

        $this->studentModel->create($params);
        return $this->Success("添加成功!");
    }

    /**
     * Edit Student
     * 
     * @access public
     * @api Student.Student/editStudent
     * @date 2025-09-13
     * @param Request $request
     * @return Json
     */
    public function editStudent(Request $request): Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, VerificationStudent::$Edit);
        if (!$validate)
            return $validate;

        $find = $this->studentModel->where("student_guid", $params["student_guid"])->find();

        if (!$find)
            return $this->ApiError("该学生不存在!");

        $find->save($params);

        return $this->Success("修改成功!");
    }

    /**
     * Delete Student
     * 
     * @access public
     * @api Student.Student/deleteStudent
     * @date 2025-09-13
     * @param Request $request
     * @return Json
     */
    public function deleteStudent(Request $request): Json
    {
        $params = $request->param();
        $validte = $this->ValidateParams($params, VerificationStudent::$Delete);
        if (!$validte)
            return $validte;

        foreach ($params["student_guid"] as $k => $v) {
            $find = $this->studentModel->where("student_guid", $v)->find();
            if (!$find)
                return $this->ApiError("没有找到对应数据!");
            $find->delete();
        }


        return $this->Success("删除成功");
    }


}