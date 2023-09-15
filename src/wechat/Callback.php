<?php
namespace wjgsxty\icbc\wechat;
use Exception;
use IcbcSignature;
use WebUtils;


class Callback{

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

	public function icbcCallback() {
		$params = array();
		$from = $_POST['from'];
		$api = $_POST['api'];
		$appId = $_POST['app_id'];
		$charset = $_POST['charset'];
		$format = $_POST['format'];
		$timestamp = $_POST['timestamp'];
		$sign_type = $_POST['sign_type'];
		$biz_content = htmlspecialchars_decode($_POST['biz_content']);
		$sign = $_POST['sign'];
		$params['from'] = $from;
		$params['api'] = $api;
		$params['app_id'] = $appId;
		$params['charset'] = $charset;
		$params['format'] = $format;
		$params['timestamp'] = $timestamp;
		$params['sign_type'] = $sign_type;
		$params['biz_content'] = $biz_content;
		error_log(json_encode($params));
		$path = "/epay/NotifyUrlServlet.php";
		$signStr = WebUtils::buildOrderedSignStr($path,$params);
		error_log("signStr:".$signStr);
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
		error_log("results======:".$results."=====!");
		
		return $results;
	}
}