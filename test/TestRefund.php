<?php
date_default_timezone_set("PRC");
try {
    include "../vendor/autoload.php";
    include "../include.php";
    $config = include "../config/config.php";

    $icbc = wjgsxty\icbc\wechat\Trans::init($config);
    $data = [
        'trade_no' => '122344', 
        'refund_trade_no' => '222234',
        'refund_fee' => '1', // 订单金额，以分为单位
    ];
    $result = $icbc->payRefund($data);
    var_export($result);

} catch (Exception $exception) {
    // 出错啦，处理下吧
    echo $exception->getMessage() . PHP_EOL;
}