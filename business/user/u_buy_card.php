<?php
	include '../db/dbconfig.php';
	header('Content-Type: text/html; charset=UTF-8');
	$result = $_POST['result'];

	if($result == 'card_payment'){
		if($conn){
			$pk_id = $_POST['pk_id'];     // pk_id
			$sql_select = " SELECT `pk_id`,`user_name`,`user_id`,`user_mobile` FROM `v_parent_node` WHERE `pk_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				$t_status = $_POST['t_status'];                     // 구분 2.월렛카드, 4.비자카드, 3.단말기
				$receiving_number = $_POST['receiving_number'];     // 받으실 전화번호
				$receiving_address = $_POST['receiving_address'];   // 받으실 주소
				$color_id = $_POST['color_id'];                     // 카드 색상
				$product_price = $_POST['price_id'];                // 결제 금액($)
				$appKey = "kp05cd41a4954f472196c7879a0dfe2760";     // 테스트 : kp45a31ee91c314790bf1f2c8258ab8e01
				$appSecret = "s83c42f3c46c34a189a8ad96ba5e3445c";   // 테스트 : s106e2c81d40d4d63a7e5019f7cde6e5a
				$time = time();
				$orderNum = $time.$pk_id;                           // (현재시간 + pk_id)
				$currencyType = 2;            	                    // (1: CNY 2: USD 3: KWR)
				$qrcodetype = "1";
				$server_prod_name = "cube_payment";
				$sign = md5("AMOUNT=".$product_price."&APPKEY=".$appKey."&CURRENCYTYPE=".$currencyType."&GOODSNAME=".$server_prod_name."&MERCHANTORDERNUM=".$orderNum."&QRCODETYPE=".$qrcodetype."&TS=".$time."&APPSECRET=".$appSecret);
				$sign = strtolower($sign);

				$value = array(
					"amount" => $product_price,
					"appKey" => $appKey,
					"currencyType" => $currencyType,
					"goodsName" => $server_prod_name,
					"merchantOrderNum" => $orderNum,
					"qrCodeType" => $qrcodetype,
					"ts" => $time,
					"sign" => $sign
				);
				$value = json_encode($value);

				$sql_insert = " INSERT INTO `v_login_payment` (`pk_id`,`agent_id`,`user_name`,`user_id`,`user_mobile`,`product_price`,`order_num`,`status`,`t_status`,`insert_time`,`update_time`,`order_value`,`receiving_number`,`receiving_address`,`color_id`) VALUES ";
				$sql_insert .= " (NULL,".$pk_id.",'".$row['user_name']."','".$row['user_id']."','".$row['user_mobile']."',".$product_price.",'".$orderNum."',0,".$t_status.",'".$time."','".$time."','".$value."','".$receiving_number."','".$receiving_address."','".$color_id."') ";
				mysqli_query($conn, $sql_insert);
			}else{
				$value = array(
					"reten" => "fail"
				);
				$value = json_encode($value);
			}
		}else{
			$value = array(
				"reten" => "fail"
			);
			$value = json_encode($value);
		}
	}else{
		$value = array(
			"reten" => "fail"
		);
		$value = json_encode($value);
	}
	mysql_close($conn);
	echo $value;
?>