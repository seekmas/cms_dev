<?php
require_once('../upyun.class.php');

$upyun = new UpYun('site-file-36lean', 'mot', 'wujiayao123');

try {
    echo "<pre>=========获取目录文件列表\r\n";
    $list = $upyun->getList('/demo/');
    var_dump($list);
    echo "=========DONE</pre>\r\n\r\n";
}
catch(Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
}
