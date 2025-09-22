<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

require __DIR__ . '/../vendor/autoload.php';




// 处理跨域 option 预检请求
 
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    // 允许的源原域名
    header('Access-Control-Allow-Origin: *');
 
    //  允许的请求头信息
    header("Access-Control-Allow-Headers:Origin, X-requested-width, Content-type, Accept, Authorization");
 
    // 允许的请求类型
    header("Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS, PATCH");
    exit;
}


 // 允许的源原域名
 header('Access-Control-Allow-Origin: *');
 
 //  允许的请求头信息
 header("Access-Control-Allow-Headers:Origin, X-requested-width, Content-type, Accept, Authorization");


try {
    // 执行HTTP应用并响应
    $http = (new App())->http;

    $response = $http->run();

    $response->send();
} catch (\InvalidArgumentException $th) {
    // 方便浏览器调试
    if (isset($response)) {
        $data = $response->getData();
        if (is_array($data)) {
            echo json_encode($data);
        }
    } else {
        throw $th;
    }
} finally {
    $http->end($response);
}
