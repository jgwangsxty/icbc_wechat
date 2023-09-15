<?php
spl_autoload_register(function ($classname) {
    $classname = str_replace('wjgsxty\icbc\\', '', $classname);
    $pathname = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
    if (file_exists($pathname . $filename)) {
        foreach (['wechat'] as $prefix) {
            if (stripos($classname, $prefix) === 0) {
                include $pathname . $filename;
                return true;
            }
        }
    }
    return false;
});