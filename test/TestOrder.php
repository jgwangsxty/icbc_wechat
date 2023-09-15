<?php
date_default_timezone_set("PRC");
try {
    include "../vendor/autoload.php";
    // 1. 手动加载入口文件
    include "../include.php";

    $config = include "../config/config.php";

    $icbc = wjgsxty\icbc\wechat\Trans::init($config);
    $data = [
        'trade_no' => '122344', 
        'mer_url' => 'https://apipcs3.dccnet.com.cn/api/cardbusiness/aggregatepay/b2c/online/consumepurchase/V1', 
        'orig_date_time' => '2023-09-14T16:20:51', 
        'spbill_create_ip' => '115.29.150.155', 
        'total_fee' => '1', // 订单金额，以分为单位
        'order_id' => '1234323232', // 订单id，唯一
        'body' => 'some info ', // 订单说明  text
    ];
    // $result = $icbc->payOrder($data);
    $result = $icbc->payRefund($data);
    var_export($result);

} catch (Exception $exception) {
    // 出错啦，处理下吧
    echo $exception->getMessage() . PHP_EOL;
}