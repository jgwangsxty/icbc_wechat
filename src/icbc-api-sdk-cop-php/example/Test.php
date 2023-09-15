<?php
include_once '../DefaultIcbcClient.php';
include_once '../IcbcConstants.php';
$request = array(
    "serviceUrl" => 'https://apipcs3.dccnet.com.cn/api/cardbusiness/aggregatepay/b2c/online/consumepurchase/V1',
    "method" => 'POST',
    "isNeedEncrypt" => false,
    "biz_content" => array(
        "mer_id"=>"100159932969",
        "mer_prtcl_no"=>"1001599329690201",
        "pay_mode"=>"9",
        "access_type"=>"9",
        "open_id" => 'ogTIEt1aKEl-IK9AWslF7E8ORO3A', 
        "shop_appid" => 'ogTIEt1aKEl-IK9AWslF7E8ORO3A', 
        "out_trade_no"=>"AAPPT20051202205754714",
        "decive_info"=>"9774d56d682e549c",
        "body"=>"勇士总冠军",
        "fee_type"=>"001",
        "icbc_appid"=>"11000000000000000901",
        "mer_acct"=>"1402027109600325515",
        "mer_url"=>"https://api-t.andaijia.com/dd/open/v1/pay/notify",
        "orig_date_time"=>"2023-09-14T16:20:51",
        "spbill_create_ip"=>"218.81.72.241",
        "total_fee"=>"1",
        "notify_type"=>"HS"
    ),
    'extraParams' => null,

);
//以下构造函数第1个参数为appid，第2个参数为RSA密钥对中私钥，第6个参数为API平台网关公钥
$client = new DefaultIcbcClient('11000000000000000901',
    'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCG9npt6+H6FMPFXlHJI5TX7UVHJSV6i43ThhY00jXoSdhddrXF2uhDoUF1SV6CktETSCigi4jws5TEob+B5PYm3n3QFelkmeiHlqzM6zzkpjov6xQ79EOExBGVXdY0aDKFiPRto7TysGp1V2OhU4ffzzBxy/vnih+xy93UL9TwU1iO/UhtfmS6LmRS45U2FiM3JrTk5/3QGry5owEa6KusqHjhq/3PygykPXhn1tJtqBD5dWgeihjvekwES0HqGsXoO8NvppwlZD7Dwei3sPDjajBraa+NRozukYdhoRDwtUd6fPMfVFaJ+KTjOzjnMbAz/nz+BuBRMTmzwa7yGxOpAgMBAAECggEAEjmimslpwK2hKjvsa28EoIwH+O2JPB0wT8ohoYxpfy7JqwVm0osXJlrWThJUUumklif+ZH+zRF3bzxnQlKfonaMZ9kmfNNib0AOG3j0+Adp4rRPfraD0pS8c+MCtNnKDsAioiU28F7G/Do14hMaU6KI/9n4HTGMYmJfhItA6vyEuLz4R/eqZOAijmLN1MQwXBvxrvLUoeCzgaJbvxdM7lK9wtkLuYJ3r8/5cujmaU2clVeMkGVLxQKlSwVHu03rA6MD0gAyA8Ies1z5jODNZUCWlvDpKfYIz2eCRMoTxI0wfOgIZ5HUniyhk18f9tyQ6bog2do1ER7yDcqPcCGv/oQKBgQDyYe78Uail6lO5dO2gfM/OKKio3MwwHInxPjHbpmT4k/gFM1eAlTQz3yNxdNDV37j6rUwMZUDUbLp4gGm8n5S7AXS8glX0cN/eJJMq/fqjtEdYiUe9BoPXH5Rk2LPjXkFCTuBbBrbvuIip2ODvud4ALWAKDPAp63GjwakyCUf+hQKBgQCOi5SYtOWuEvfHO/EKoqzJtxegRsuklk+T18ti/SJoaRNXHDQSrS8GefD9ChjjeZW9lCzFo61HajP0TJx/aDbjZu0O4bckaBNK9UpS2o5lnNsNDc+aBFbyyI4VbbtCvUkxmQgHvIpPYOo5ZqmAVg6fktYTLDjv/XlgKrIvJZHD1QKBgFKufaus327ZpH3bGURpzylwTThtOWogEh4tLSzUchUpxK1hejPuscQQFjMZujN7AcrhWtPPpnHQNTvt8iPZ/A5ezMeRXmjoKDXLHDjKMrmtQbk9+y7MDiVQLHrKQXMKMBDCf14NyFG2DUiDJxgfu08sK1rCVxq1qipGYcUjzV9RAoGAbCbDK8m0qgqCLIvyKpuadcGRD24nfEz/O7DzA7wDqyxuHB2t9K2pjERg79wFXjIVxqzx+1JgWlrd6HFoG9K6MncZTb0780dzu9+38H0apUYJfTIJXVYFkldQVjnXZp+vNQ5i5VvLpMZnb/3QhSrGFZSXEBzxZkTvHmPGpbbfjbECgYAQqgz9gLnjy8pTTr9mp4krXBFds/75XsQ+iDDxGRIsTa+XxPJt+xlDTvmAm4wYg+aCLdtaq4ZUo31p81ZVQpUDFbtrDZ7xTik6fVL4scVaw73wdHj1JZka7DD+AVjCFaMFMtDcs8LU4gNCSQpErHjkVo84huA0QokdIEn9Vq0wuQ==',
    IcbcConstants::$SIGN_TYPE_RSA2,
    '',
    '',
    'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwFgHD4kzEVPdOj03ctKM7KV+16bWZ5BMNgvEeuEQwfQYkRVwI9HFOGkwNTMn5hiJXHnlXYCX+zp5r6R52MY0O7BsTCLT7aHaxsANsvI9ABGx3OaTVlPB59M6GPbJh0uXvio0m1r/lTW3Z60RU6Q3oid/rNhP3CiNgg0W6O3AGqwIDAQAB',
    '',
    '',
    '',
    '');
$resp = $client->execute($request,'1231311231144','');//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
echo $resp;
$respObj = json_decode($resp,true);
if($respObj["return_code"] == 0){ //sucess
    echo $respObj["return_msg"];
}else{//fail
    echo $respObj["return_msg"];
}

?>