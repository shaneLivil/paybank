<?php

header('Content-Type: text/html; charset=UTF-8');
require_once './class/GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();

//$secret = $ga->createSecret(); // 시크릿키 생성
$secret = "WKU5LIDM2TLC775Y"; // 
$qrCodeUrl = $ga->getQRCodeGoogleUrl("uid_471", $secret);
$oneCode = $ga->getCode($secret);

$otc_code = array(
	"otc_code" =>  urlencode($qrCodeUrl),
	"secret" =>  $secret
);

$otc_code = json_encode($otc_code, JSON_UNESCAPED_UNICODE);
echo $otc_code;
?>
<img src="qr.php?images=<?php echo urlencode($qrCodeUrl); ?>" alt="" />