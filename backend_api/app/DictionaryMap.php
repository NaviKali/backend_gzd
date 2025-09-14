<?php

// +----------------------------------------------------------------------
// | DictionaryMap
// | Date:2024-11-12
// | By:LIULIE
// +----------------------------------------------------------------------


namespace app;

class DictionaryMap
{
    use \app\common\trait\DictionaryMap;
    /**
     * Get DictionaryMap [admin . api]
     * @author liulei
     * @static
     * @access public
     * @var array
     */
    public static array $dictionaryMap = [
        "admin" => [
            "login" => [
                "login_status" => [
                    0 => "允许",
                    1 => "禁止",
                ]
            ],
            "user"=>[
                "user_sex"=>[
                    1 => "男",
                    2 => "女",
                ]
            ],
            "student"=>[
                "student_sex"=>[
                    1 => "男",
                    2 => "女",
                ],
            ],
        ],
        "api" => [],
    ];

}