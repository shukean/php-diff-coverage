<?php

include __DIR__.'/Coverage.php';

$files = [
    [
        '/Users/yky/work/beequick.cn/svn/trunk.api/application/controllers/App.php',
        '/Users/yky/work/beequick.cn/svn/trunk.api/application/controllers/Notice.php'
    ]
];

$phpunit_dir = [
    '/Users/yky/work/beequick.cn/svn/trunk.api/test/html/'
];

$diff_ret = coverage($files, $phpunit_dir, '/Users/yky/work/beequick.cn/svn/trunk.api/application/');

foreach ($diff_ret as $file => $diff){
    if (!empty($diff)){
        echo $file;
        print_r($diff);
    }
}