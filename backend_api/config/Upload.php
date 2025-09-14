<?php

#|----------------------------------|
#|       WECLOME TANK               |
#|     I LIKE PHP FROM THIS         |
#|      @email 2149573631@qq.com    |
#|----------------------------------|


namespace config;

/**
 * (配置)文件上传
 */

return [
        /**磁盘文件 */
        "disk" => "public/upload",
        /**文件后缀支持类型 */
        "fileSuffixSupportType" => [
                "txt",
                "pdf",
                "png",
                "jpg",
                "jpeg",
                "php",
                "c",
                "sql",
        ],
];
