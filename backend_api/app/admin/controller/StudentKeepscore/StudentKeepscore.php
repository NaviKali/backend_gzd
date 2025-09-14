<?php

namespace app\admin\controller\StudentKeepscore;

use app\Base;
use app\Request;
use think\response\Json;
use app\admin\verification\StudentKeepscore\StudentKeepscore as verifictaionStudentKeepscore;
use app\admin\model\StudentKeepscore\StudentKeepscore as ModelStudentKeepscore;


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

        if (count($params["student_guid"]) != count($params["student_keepscore_type_guid"]))
            return $this->ApiError("数据异常!");

        foreach ($params["student_guid"] as $k => $v) {
            $this->model->create([
                "student_guid" => $v,
                "student_keepscore_type_guid" => $params["student_keepscore_type_guid"][$k],
            ]);
        }

        return $this->Success("添加成功!");
    }
    /**
     * 获取学生记分列表
     */
    public function getStudentKeepscoreList(Request $request): Json
    {
        $query = $this->model->field([]);

        $count = $query->count();
        
        $list = self::PagePacka($query, $request->param())
            ->order('create_datetime',"desc")
            ->select();


        return $this->Success("获取成功!", ["list" => $list, "count" => $count]);

    }

}