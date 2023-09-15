<?php
namespace wjgsxty\icbc\wechat;
use DefaultIcbcClient;
use Exception;
use IcbcConstants;

class Trans {

    protected $config ;

    public function __construct($config){
        $this->checkConfig($config);
        $this->config = $config ;
    }

    private function checkConfig($config) {
        if(empty($config) ) {
            throw new Exception("请输入 config 参数！");
        }
        foreach(TransEnum::$configParams as $k=>$v) {
            if(!isset($config[$v])) {
                throw new Exception("该参数:" .$v  . " 不能为空！");
            }
        }
        return;
    }

    private  function checkoutParams($method, $data) {
        if(empty($data) || empty($method)) {
            throw new Exception("请输入参数！");
        }
        if(!isset(TransEnum::$methodParams[$method])) {
            throw new Exception("该方法:" . $method . " 没有配置参数信息！");
        }
        if(!isset(TransEnum::$methodParams[$method]['required'])) {
            throw new Exception("该方法:" . $method . " 没有配置 required 信息");
        }
        $required = TransEnum::$methodParams[$method]['required'];
        if(empty($required)) {
            throw new Exception("该方法:" . $method . " 的 required 信息为空！");
        }
        foreach($required as $k=>$v) {
            if(!isset($data[$v])) {
                throw new Exception("该方法:" . $method . " 的参数:" .$v  . " 不能为空！");
            }
        }
        return ;
    }
    private function createMsgId() {
        $id = date("YmdHis" , rand(1000, 9999));

        return $id;
    }
    /**
     * 支付下单
     */
    public function payOrder($data){

        $request = $this->createPayOrderReq($data);
        $client = new DefaultIcbcClient( $this->config['app_id'], $this->config['privateKey'],
            IcbcConstants::$SIGN_TYPE_RSA2,'','',
            $this->config['icbcPublicKey'],'','','','');
        $resp = $client->execute($request, $this->createMsgId(),'');//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
        // echo $resp;
        $respObj = json_decode($resp,true);
        // if($respObj["return_code"] == 0){ //sucess
        //     echo $respObj["return_msg"];
        // }else{//fail
        //     echo $respObj["return_msg"];
        // }
        return $respObj;
    }

    public static function init($config) {
        return new static($config);
    }

    private function createPayOrderReq($data) {
        $this->checkoutParams("payOrder", $data);
        $origDateTime = str_replace(" ", "T", $data['orig_date_time']);
        $url = $this->config['icbc_domain'] . $this->config['pay_order_url'];
        $request = array(
            "serviceUrl" => $url,
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id"=> $this->config['mer_id'],
                "mer_prtcl_no"=>  $this->config['mer_prtcl_no'],
                "pay_mode"=>"9",
                "access_type"=>"9",
                "open_id" =>  $this->config['open_id'], 
                "shop_appid" =>  $this->config['open_id'],
                "out_trade_no"=> $data['trade_no'], // 调用方的订单号，必须唯一
                "decive_info"=>"anshifu_icbc",
                "body"=> $data['body'],
                "fee_type"=>"001",
                "icbc_appid"=>$this->config['app_id'], 
                "mer_url"=> $data['mer_url'],
                "orig_date_time"=> $origDateTime,
                "spbill_create_ip"=> $data['spbill_create_ip'],
                "total_fee"=>$data['total_fee'],
                "notify_type"=>"HS"
            ),
            'extraParams' => null,
        
        );

        return $request;
    }
    /**
     * 支付结果回调
     */
    public function payCallback(){

    }

    private function createRefundRequest($data) {
        $url = $this->config['icbc_domain'] . $this->config['pay_refund_url'];
        // echo $url;
        $request = array(
            "serviceUrl" => $url,
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id"=> $this->config['mer_id'],
                "mer_prtcl_no"=>  $this->config['mer_prtcl_no'],
                "icbc_appid"=>$this->config['app_id'], 
                "oper_flag"=>"1",//0－子订单退货 ，1 - 消费退货
                // "sub_order_id"=>"20001040336202005261544015",//子订单编号唯一 
                "seq_no"=>$data['trade_no'],
                "busi_type"=>"2",//原交易类型，2-使用工行订单号
                // "ori_mer_id"=>"020001040311",//原交易商户编号 
                // "sub_mer_id"=>"020001040336",//商户编号 
                "sub_mer_prtcl_no"=>"0200010403360201",//协议编号 
                "ret_sub_order_id"=>"113674649",
                "outtrx_serial_no"=>"",//大订单加送字段
                "classify_amt"=>"2",//清算金额 
                "ori_trx_date"=>"2020-09-30",
                "mer_acct" =>""//清算账号
            ),
            'extraParams' => null,
        );

        return $request;
    }

    /**
     * 支付退款
     */
    public function payRefund($data){
        $request = $this->createRefundRequest($data);
        $client = new DefaultIcbcClient($this->config['app_id'], $this->config['privateKey'],
        IcbcConstants::$SIGN_TYPE_RSA2,'','','','','','','');

        $resp = $client->execute($request, $this->createMsgId(),'');//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
        $respObj = json_decode($resp,true);
        echo $respObj;
        // if($respObj["return_code"] == 0){ //sucess
        //     echo $respObj["return_msg"];
        // }else{//fail
        //     echo $respObj["return_msg"];
        // }
        return $respObj;
    }
}