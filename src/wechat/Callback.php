<?php
namespace wjgsxty\icbc\wechat;
use Exception;
use wjgsxty\icbc\IcbcConstants;
use wjgsxty\icbc\IcbcSignature;
use wjgsxty\icbc\WebUtils;



class Callback{

	protected $config ;

	public static function init($config) {
        return new static($config);
    }

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

	public function icbcCallback() {
		// $params = array();
		// $from = $_POST['from'];
		// $api = $_POST['api'];
		// $appId = $_POST['app_id'];
		// $charset = $_POST['charset'];
		// $format = $_POST['format'];
		// $timestamp = $_POST['timestamp'];
		// $sign_type = $_POST['sign_type'];
		// $biz_content = htmlspecialchars_decode($_POST['biz_content']);
		// $sign = $_POST['sign'];
		// $params['from'] = $from;
		// $params['api'] = $api;
		// $params['app_id'] = $appId;
		// $params['charset'] = $charset;
		// $params['format'] = $format;
		// $params['timestamp'] = $timestamp;
		// $params['sign_type'] = $sign_type;
		// $params['biz_content'] = $biz_content;
		// error_log(json_encode($params));
		// $path = "/callback/icbc";
		// $signStr = WebUtils::buildOrderedSignStr($path,$params);
		$biz_content = '{"access_type":"9","attach":"","bank_disc_amt":"0","card_flag":"","card_kind":"","card_no":"","coupon_amt":"0","cust_id":"","decr_flag":"","ecoupon_amt":"0","mer_disc_amt":"0","mer_id":"100159932969","msg_id":"050258363262818132041002547","open_id":"","order_id":"100159932969000522309190000065","out_trade_no":"20230919111830181","pay_time":"20231025000001","pay_type":"9","payment_amt":"1","point_amt":"0","return_code":"1","return_msg":"交易失败","third_party_coupon_amt":"0","third_party_discount_amt":"0","third_trade_no":"","total_amt":"1","total_disc_amt":"0"}';
		$signStr = '//callback/icbc?api=/api/cardbusiness/aggregatepay/b2c/online/consumepurchase/V1&app_id=11000000000000000901&biz_content={"access_type":"9","attach":"","bank_disc_amt":"0","card_flag":"","card_kind":"","card_no":"","coupon_amt":"0","cust_id":"","decr_flag":"","ecoupon_amt":"0","mer_disc_amt":"0","mer_id":"100159932969","msg_id":"050258363262818132041002547","open_id":"","order_id":"100159932969000522309190000065","out_trade_no":"20230919111830181","pay_time":"20231025000001","pay_type":"9","payment_amt":"1","point_amt":"0","return_code":"1","return_msg":"交易失败","third_party_coupon_amt":"0","third_party_discount_amt":"0","third_trade_no":"","total_amt":"1","total_disc_amt":"0"}&charset=UTF-8&format=json&from=icbc-api&sign_type=RSA&timestamp=2023-09-19 13:21:52';
		error_log("signStr:".$signStr);
		$sign_type = 'RSA';
		$charset = 'UTF-8';
		$sign = 'bUvdKAPYn+C9eCG5mIROfpsFvchRyVHxyaViFSkK9JQqMdjfKzWeP86ay47H4o6u0Q4v4ugpTOmu1RGbtd4ndnZn0wY23FwwvdUjhxZVeK9XXEWSuzRtAt\/\/s1fsWsxUjNR3AHBVnMaiSki\/NDSWpOsclueMs0h1Oy8d6JRbASI=';
		$icbcPublicKey = $this->config['icbcPublicKey'];
		$passed = IcbcSignature::verify($signStr, $sign_type, $icbcPublicKey, $charset, $sign,'');
		error_log("passed:".$passed);
		$responseBizContent = "";
		if(!$passed){
				$responseBizContent = "{\"return_code\":-12345,\"return_msg\":\"icbc sign not pass.\"}";
		}else{
			$respMap = json_decode($biz_content,true);
			$msg_id = $respMap["msg_id"];
			//$msg_id = time();
			$return_code = 0;
			$eturn_msg = "success.";
			$responseBizContent = "{\"return_code\":".$return_code.",\"return_msg\":\"".$eturn_msg."\",\"msg_id\":\"".$msg_id."\"}";
			
			error_log("responseBizContent======:".$responseBizContent."=====!");
		}
		$privateKey = $this->config['privateKey'];
		$signStr = "\"response_biz_content\":".$responseBizContent.","."\"sign_type\":"."\"RSA2\"";
		$sign = IcbcSignature::sign($signStr, IcbcConstants::$SIGN_TYPE_RSA2, $privateKey,$charset,"");
		$results = "{".$signStr.",\"sign\":\"".$sign."\"}";
		error_log("xxxxxxxx");
		error_log("results======:".$results."=====!");
		
		return $results;
	}
}