<?php

namespace app\admin\verification\StudentKeepscore\StudentKeepscoreType;


class StudentKeepscoreType
{

    public static array $Add = [
        "student_keepscore_type_name" => "记分类型名称",
        "keepscore_num" => "记分分数",
    ];
    public static array $Edit = [
        "student_keepscore_type_guid" => "记分类型Guid",
        "student_keepscore_type_name" => "记分类型名称",
        "keepscore_num" => "记分分数",
    ];
    public static array $Delete = [
        "student_keepscore_type_guid" => "记分类型Guid",
    ];

}