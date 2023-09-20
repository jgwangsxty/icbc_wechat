<?php
namespace wjgsxty\icbc\wechat;
use Exception;
use wjgsxty\icbc\DefaultIcbcClient;
use wjgsxty\icbc\IcbcConstants;

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
        $respObj = json_decode($resp,true);
        
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
                "open_id" =>  $data['user_open_id'], 
                "shop_appid" =>  $this->config['wechat_app_id'],
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
        $request = array(
            "serviceUrl" =>  $url,
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id"=> $this->config['mer_id'],
                "outtrx_serial_no"=> $data['refund_trade_no'], //外部退货流水号
                "order_id"=> "", //消费工行订单号
                "out_trade_no"=> $data['pay_trade_no'], //外部订单号
                "ret_total_amt"=> $data['refund_fee'], //退货总金额
                "trnsc_ccy"=>"1", //交易币种
                "icbc_appid"=> $this->config['app_id'], //商户在工行API平台的APPID
                "mer_acct"=>"", //商户清算账号
                "order_apd_inf"=>"apd_inf" //订单附加信息，订单关联
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
            IcbcConstants::$SIGN_TYPE_RSA2,'','',
            $this->config['icbcPublicKey'],'','','','');

        $resp = $client->execute($request, $this->createMsgId(),'');//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
        $respObj = json_decode($resp,true);
        
        return $respObj;
    }
}