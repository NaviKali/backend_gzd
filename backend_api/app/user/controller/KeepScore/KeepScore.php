<?php

namespace app\user\controller\KeepScore;

use app\Base;
use app\Request;
use app\View;
use think\facade\Db;
use app\admin\model\StudentKeepscore\StudentKeepscore as ModelStudentKeepscore;
use app\admin\model\StudentKeepscore\StudentKeepscoreType\StudentKeepscoreType as ModelStudentKeepscoreType;
use app\admin\model\Student\Student as ModelStudent;

class KeepScore extends View
{

    /**
     * 添加学生记分
     * 
     * @access public
     * @param Request $request
     * @return \think\response\Json
     */
    public function addStudentKeepscore(Request $request): \think\response\Json
    {
        $params = $request->param();

        for ($i = 0; $i < $params["count"]; $i++) {
            (new ModelStudentKeepscore)->create([
                "student_guid" => (new ModelStudent())->where('student_number', $params["student_number"])->value("student_guid"),
                "student_keepscore_type_guid" => $params["student_keepscore_type_guid"],
                "student_keepscore_date" => $params["student_keepscore_date"]
            ]);
        }

        $score = $params["count"] * (new ModelStudentKeepscoreType)->where('student_keepscore_type_guid', $params['student_keepscore_type_guid'])->value("keepscore_num");
        (new ModelStudent())->handleStudentScoreCount($params["student_number"], $score);

        return (new Base)->Success("添加成功!");
    }
    /**
     * 小组记分
     * 
     * @access public
     * @return \think\response\View
     */
    public function Keep(Request $request): \think\response\View
    {
        $params = $request->param();

        //*获取小组长下的组员
        $TeamStudents = explode(",", Db::name('student_team')->where('student_team_student_boss_number', $params["student_number"])
            ->value('student_team_students')) ?? [];

        $TeamStudents[] = $params["student_number"];

        $data['students'] = Db::name("student")->whereIn('student_number', $TeamStudents)
            ->field([
                'student_name',
                'student_number',
            ])
            ->order('student_number', 'asc')
            ->select()->toArray();

        $data['student_keepscore_type'] = Db::name('student_keepscore_type')
            ->where('delete_datetime', null)
            ->field([
                'student_keepscore_type_guid',
                'student_keepscore_type_name',
                'keepscore_num',
            ])
            ->order("create_datetime", 'asc')
            ->select()->toArray();


        $this->data["Data"] = $data;
        return view("/KeepScore/keep", $this->data);
    }

    /**
     * 小组长登录
     * 
     * @access public
     * @param \app\Request $request
     * @return \think\response\Json
     */
    public function teamLeaderLogin(Request $request): \think\response\Json
    {
        $studentNumber = $request->param("student_number");

        //?是否为小组长
        $find = Db::name("student_team")->where('student_team_student_boss_number', $studentNumber)->find();
        if (!$find)
            return (new Base)->ApiError("您不是小组长!");

        $student = Db::name("student")->where('student_number', $studentNumber)
            ->field([
                'student_number',
                'student_name',
            ])->find();
        if (!$student)
            return (new Base)->ApiError("数据异常!");

        return (new Base())->Success("欢迎回来!", [
            "student" => $student
        ]);
    }

    /**
     * Login View
     * 
     * @access public
     * @return \think\response\View
     */
    public function index(): \think\response\View
    {
        return view("/KeepScore/index", $this->data);
    }
}