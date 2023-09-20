<?php
namespace wjgsxty\icbc\wechat;
use Exception;

class TransEnum {

    /**
     * 必须的配置参数
     */
    public static $configParams = [
        'icbc_domain' ,
        'pay_order_url' ,
        'pay_refund_url' ,
        "mer_id",
        "mer_prtcl_no",
        'app_id',
        'privateKey',
        'icbcPublicKey',
    ];
    /**
     * 方法参数
     */
    public static $methodParams = [
        'payOrder' => [ // 支付下单
            'required' => [
                'mer_url', 'orig_date_time', 'spbill_create_ip', 
                'total_fee', // 订单金额，以分为单位
                'trade_no', // 交易编号，唯一
                'body', // 订单说明  text
                'user_open_id', // 下单用户的openid
            ], 
            'optional' => [ 'method'],
        ], 
        'payCallback' => [ // 回调
            'required' => [], 
            'optional' => [ ],
        ], 
        'payRefund' => [ // 退款
            'required' => [
                'refund_trade_no',
                'pay_trade_no', // 原支付交易号
                'refund_fee', 
            ], 
            'optional' => [],
        ], 
    ];
   
}