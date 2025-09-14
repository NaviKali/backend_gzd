<?php

namespace app\admin\controller\StudentKeepscore\StudentKeepscoreType;

use app\Base;
use app\Request;
use think\response\Json;
use app\admin\model\StudentKeepscore\StudentKeepscoreType\StudentKeepscoreType as ModelStudentKeepscoreType;
use app\admin\verification\StudentKeepscore\StudentKeepscoreType\StudentKeepscoreType as verificationStudentKeepscoreType;

class StudentKeepscoreType extends Base
{

    public ModelStudentKeepscoreType $model;

    public function __construct()
    {
        $this->model = (new ModelStudentKeepscoreType);
    }

    /**
     * 删除记分类型列表
     * 
     * @access public
     * @api StudentKeepscore.StudentKeepscoreType.StudentKeepscoreType/deleteStudentKeepscoreType
     * @date 2025-09-13
     * @param Request $request
     * @return Json
     */
    public function deleteStudentKeepscoreType(Request $request): Json
    {
        $params = $request->param();

        $validate = $this->ValidateParams($params, verificationStudentKeepscoreType::$Delete);
        if (!$validate)
            return $validate;

        foreach ($params['student_keepscore_type_guid'] as $k => $v) {
            $find = $this->model->where('student_keepscore_type_guid', $v)->find();
            if (!$find)
                return $this->ApiError("没有找到对应记分类型!");
            $find->delete();
        }

        return $this->Success("删除成功!");
    }

    /**
     * 添加记分类型列表
     * 
     * @access public
     * @api StudentKeepscore.StudentKeepscoreType.StudentKeepscoreType/editStudentKeepscoreType
     * @date 2025-09-13
     * @param Request $request
     * @return Json
     */
    public function editStudentKeepscoreType(Request $request): Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, verificationStudentKeepscoreType::$Edit);
        if (!$validate)
            return $validate;

        $find = $this->model->where('student_keepscore_type_guid', $params['student_keepscore_type_guid'])->find();
        if (!$find)
            return $this->ApiError("没有找到对应记分类型!");

        $find->save([
            "student_keepscore_type_name" => $params['student_keepscore_type_name'],
            "keepscore_num" => $params['keepscore_num'],
        ]);

        return $this->Success("修改成功!");
    }

    /**
     * 添加记分类型列表
     * 
     * @access public
     * @api StudentKeepscore.StudentKeepscoreType.StudentKeepscoreType/addStudentKeepscoreType
     * @date 2025-09-13
     * @param Request $request
     * @return Json
     */
    public function addStudentKeepscoreType(Request $request): Json
    {
        $params = $request->param();
        $validate = $this->ValidateParams($params, verificationStudentKeepscoreType::$Add);
        if (!$validate)
            return $validate;

        $find = $this->model->where('student_keepscore_type_name', $params['student_keepscore_type_name'])->find();
        if ($find)
            return $this->ApiError("该记分类型已存在!");

        $this->model->create($params);

        return $this->Success("添加成功!");

    }

    /**
     * 获取记分类型列表
     * 
     * @access public
     * @api StudentKeepscore.StudentKeepscoreType.StudentKeepscoreType/getStudentKeepscoreTypeList
     * @date 2025-09-13
     * @param Request $request
     * @return Json
     */
    public function getStudentKeepscoreTypeList(Request $request): Json
    {
        $query = $this->model->field([
            "student_keepscore_type_id" => "key",
            "student_keepscore_type_guid",
            "student_keepscore_type_name",
            "keepscore_num",
            "create_datetime",
        ]);

        $count = $query->count();

        $list = self::PagePacka($query, $request->param())->order('create_datetime', 'desc')->select();

        return $this->Success("获取成功!", ["list" => $list, "count" => $count]);
    }
}