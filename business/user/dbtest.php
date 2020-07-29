<?php
$_GET     && SafeFilter($_GET);
$_POST    && SafeFilter($_POST);
  
function SafeFilter (&$arr) 
{
     
   $ra=Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/','/script/','/javascript/','/vbscript/','/expression/','/applet/','/meta/','/xml/','/blink/','/link/','/style/','/embed/','/object/','/frame/','/layer/','/title/','/bgsound/','/base/','/onload/','/onunload/','/onchange/','/onsubmit/','/onreset/','/onselect/','/onblur/','/onfocus/','/onabort/','/onkeydown/','/onkeypress/','/onkeyup/','/onclick/','/ondblclick/','/onmousedown/','/onmousemove/','/onmouseout/','/onmouseover/','/onmouseup/','/onunload/');
     
   if (is_array($arr))
   {
     foreach ($arr as $key => $value) 
     {
        if (!is_array($value))
        {
          if (!get_magic_quotes_gpc())           
          {
             $value  = addslashes($value);           
          }
          $value       = preg_replace($ra,'',$value);    
          $arr[$key]     = htmlentities(strip_tags($value)); 
        }
        else
        {
          SafeFilter($arr[$key]);
        }
     }
   }
}

$result = $_POST['result'];
if($result){ $conn_2 = mysqli_connect("10.0.0.73:6638", "ffk_user", "dq3^NP1BlJaBFw^a", "paybank") or die('FAIL'); }
else{ exit(); }

if($result == 'store_list'){
	$object_store = $_POST['data'];
	$ss_select = " SELECT ";
	for($oo=0; $oo<5; $oo++){
		$tmp = '';
		$ccount = 0;
		$ss_select .= " (SELECT SUM(pay_fee) FROM `pay_order` WHERE ( ";
		if($oo == 0){ $tmp = 'PAX'; $ccount = 5; }
		else if($oo == 1){ $tmp = 'BTC'; $ccount = 1; }
		else if($oo == 2) { $tmp = 'ETH'; $ccount = 7; }
		else if($oo == 3) { $tmp = 'LTC'; $ccount = 11; }
		else if($oo == 4) { $tmp = 'USDT'; $ccount = 4; }

		for($i=0; $i<count($object_store); $i++){
			if($i == 0){
				$ss_select .= " merchant_id = ".$object_store[$i]['store_id']." AND update_time > '".$object_store[$i]['insert_time']."' ";
			}else{
				$ss_select .= " OR merchant_id = ".$object_store[$i]['store_id']." AND update_time > '".$object_store[$i]['insert_time']."' ";
			}
		}
		if($oo == 4) $ss_select .= " ) AND (receive_coin_id = ".$ccount.")) ".$tmp." ";
		else $ss_select .= " ) AND (receive_coin_id = ".$ccount.")) ".$tmp.", ";
	}
	$ss_select .= " FROM `pay_order` LIMIT 1 ";
	$result_p = mysqli_query($conn_2, $ss_select);
	$row_p = mysqli_fetch_array($result_p);
	$row_p = json_encode($row_p, JSON_UNESCAPED_UNICODE);
	echo $row_p;
}else if($result == 'insert_uid'){
	$uid = $_POST['data'];
	$sql_s = " SELECT `id`,`user_name` FROM `sys_user` WHERE `id` = ".$uid." ";
	$result_s = mysqli_query($conn_2, $sql_s);
	$row_s = mysqli_fetch_array($result_s);

	if($row_s['id']){
		$res = array(
			"id" => $row_s['id'],
			"user_name" => $row_s['user_name']
		);
		$res = json_encode($res, JSON_UNESCAPED_UNICODE);
		echo $res;
	}else{
		echo "NO";
	}
}else if($result == 'settlement_ajax'){
	$object_store = $_POST['data'];
	$ss_select = " SELECT ";
	for($oo=0; $oo<5; $oo++){
		$tmp = '';
		$ccount = 0;
		$ss_select .= " (SELECT SUM(pay_fee) FROM `pay_order` WHERE ( ";
		if($oo == 0){ $tmp = 'PAX'; $ccount = 5; }
		else if($oo == 1){ $tmp = 'BTC'; $ccount = 1; }
		else if($oo == 2) { $tmp = 'ETH'; $ccount = 7; }
		else if($oo == 3) { $tmp = 'LTC'; $ccount = 11; }
		else if($oo == 4) { $tmp = 'USDT'; $ccount = 4; }

		for($i=0; $i<count($object_store); $i++){
			if($i == 0){
				$ss_select .= " merchant_id = ".$object_store[$i]['store_id']." AND update_time > '".$object_store[$i]['insert_time']."' ";
			}else{
				$ss_select .= " OR merchant_id = ".$object_store[$i]['store_id']." AND update_time > '".$object_store[$i]['insert_time']."' ";
			}
		}
		if($oo == 4) $ss_select .= " ) AND (receive_coin_id = ".$ccount.")) ".$tmp." ";
		else $ss_select .= " ) AND (receive_coin_id = ".$ccount.")) ".$tmp.", ";
	}
	
	$ss_select .= " FROM `pay_order` LIMIT 1 ";

	$result_p = mysqli_query($conn_2, $ss_select);
	$row_p = mysqli_fetch_array($result_p);
	$row_p = json_encode($row_p, JSON_UNESCAPED_UNICODE);
	echo $row_p;
}else if($result == 'referrer_two'){
	$data = $_POST['data'];
	$data = json_decode($data, JSON_UNESCAPED_UNICODE);

	$select_cardrz = " SELECT id, cardrz FROM sys_user WHERE id IN ( ";
	foreach($data as $row){
		if($row['p_uid'] != 0){
			$select_cardrz .= $row['p_uid'].",";
		}
	}
	$select_cardrz = substr($select_cardrz, 0, -1);
	$select_cardrz .= " ) ";
	$result_cardrz = mysqli_query($conn_2, $select_cardrz);
	$data_2 = array();
	while($row_2 = mysqli_fetch_array($result_cardrz)){ 
		$data_2[] = $row_2; 
	}
	$data_2 = json_encode($data_2, JSON_UNESCAPED_UNICODE);
	echo $data_2;
}else if($result == 'add_store'){
	$store_id = $_POST['data'];
	$sql_select_pay_merchant = " SELECT * FROM `pay_merchant` WHERE `id` = ".$store_id." AND `status` = 2 ";
	$result_pay_merchant = mysqli_query($conn_2, $sql_select_pay_merchant);
	$row_pay_merchant = mysqli_fetch_array($result_pay_merchant);
	$row_pay_merchant = json_encode($row_pay_merchant, JSON_UNESCAPED_UNICODE);
	echo $row_pay_merchant;
}else if($result == 'shoping_detail'){
	$uid = $_POST['data'];
	$sql_s = " SELECT `id`,`user_name` FROM `sys_user` WHERE `id` = ".$uid." ";
	$result_s = mysqli_query($conn_2, $sql_s);
	$row_s = mysqli_fetch_array($result_s);
	$row_s = json_encode($row_s, JSON_UNESCAPED_UNICODE);
	echo $row_s;
}else if($result == 'u_queren_button'){
	if($conn_2){
		$p_uid = $_POST['data'];
		$sql_s = " SELECT `id`,`user_name`,`idcard` FROM `sys_user` WHERE `id` = ".$p_uid." ";
		$result_s = mysqli_query($conn_2, $sql_s);
		$row_s = mysqli_fetch_array($result_s);

		if($row_s['id']){
			$arr = array(
				"code" => 1,
				"id" => $row_s['id'],
				"user_name" => $row_s['user_name'],
				"idcard" => $row_s['idcard']
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "NO"
			);
		}
	}else{
		$arr = array(
			"code" => 0,
			"message" => "FAIL"
		);
	}
	$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
	echo $arr;
}else if($result == 'u_withdraw_action'){
	if($conn_2){
		$p_uid = $_POST['data'];
		$sql_s = " SELECT `id`,`user_name` FROM `sys_user` WHERE `id` = ".$p_uid." ";
		$result_s = mysqli_query($conn_2, $sql_s);
		$row_s = mysqli_fetch_array($result_s);

		if($row_s['id']){
			$arr = array(
				"code" => 1,
				"id" => $row_s['id'],
				"user_name" => $row_s['user_name']
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "NO"
			);
		}
	}else{
		$arr = array(
			"code" => 0,
			"message" => "FAIL"
		);
	}
	$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
	echo $arr;
}

mysql_close($conn_2);
?>