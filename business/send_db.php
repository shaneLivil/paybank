<?php
	include './db/dbconfig.php';
	header('Content-Type: text/html; charset=UTF-8');
	date_default_timezone_set('Asia/Seoul');
	$result = $_POST['result'];
	$data = file_get_contents('php://input');
	$http_url = 'http://agent.paybank.com/business/dbtest.php';

	if($data && $result == ""){
		if($conn){
			$json_arr = json_decode($data,TRUE);
			$order_num = $json_arr['merchantOrderNum'];
			$sql_select = " SELECT `pk_id`,`agent_id`,`user_name`,`user_id`,`user_mobile`,`product_price`,`t_status` FROM `v_login_payment` WHERE `order_num` = '".$order_num."' AND `status` = 0 ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);
			$time = time();

			$sql_update = " UPDATE `v_login_payment` SET `status` = 1, `update_time` = '".$time."' WHERE `order_num` = '".$order_num."' AND `status` = 0 ";
			mysqli_query($conn, $sql_update);

			if($row['agent_id']){
				if($row['t_status'] == 1){
					// 로그인 결제
					$start_s_level = 5;
					$vat = $row['product_price'] * 10 / 100;

					$sql_p_u = " UPDATE `v_parent_node` SET `member_rock` = 1, `my_money` = `my_money` + 46.75, `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level.", `support_money_tow_2` = `support_money_tow_2` + ".$start_s_level."  WHERE `pk_id` = ".$row['agent_id']." ";
					mysqli_query($conn, $sql_p_u);
					$sql_p_s = " SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
					$res_p_s = mysqli_query($conn, $sql_p_s);
					$row_p_s = mysqli_fetch_array($res_p_s);
					if($row_p_s['parent_id'] != 0){
						$sql_w_u = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + 4.675 WHERE `agent_id` = ".$row_p_s['parent_id']." ";
						mysqli_query($conn, $sql_w_u);
						$sql_j_i = " INSERT INTO `v_join_bonus` (`pk_id`,`agent_id`,`parent_id`,`parent_pax`,`percent`,`insert_time`) VALUES ";
						$sql_j_i .= " (NULL,".$row_p_s['parent_id'].",".$row['agent_id'].",4.675,'10','".$time."') ";
						mysqli_query($conn, $sql_j_i);

						$sql_s_count = " SELECT COUNT(*) AS `count` FROM `v_parent_node` WHERE `parent_id` = ".$row_p_s['parent_id']." AND `member_rock` = 1 ";
						$res_count = mysqli_query($conn, $sql_s_count);
						$row_count = mysqli_fetch_array($res_count);

						if($row_count['count'] > 4){
							// $sql_u_count = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + 35 WHERE `agent_id` = ".$row_p_s['parent_id']." ";
							// mysqli_query($conn, $sql_u_count);
							// $sql_i_count = " INSERT INTO `v_repurchase_bonus` (`pk_id`,`agent_id`,`parent_pax`,`insert_time`) VALUES (NULL,".$row_p_s['parent_id'].",35,'".$time."') ";
							// mysqli_query($conn, $sql_i_count);
							$sql_uu_p = " UPDATE `v_parent_node` SET `member_rock` = 1 WHERE `pk_id` = ".$row_p_s['parent_id']." ";
							mysqli_query($conn, $sql_uu_p);
						}
					}
					$sql_l_d = " DELETE FROM `v_login_payment` WHERE `agent_id` = ".$row['agent_id']." AND `status` = 0 AND `t_status` = 1 ";
					mysqli_query($conn, $sql_l_d);
					$sql_i_vat = " INSERT INTO `v_vat_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES (NULL,".$row['agent_id'].",".$vat.",'10','".$time."') ";
					mysqli_query($conn, $sql_i_vat);

					$agent_id_1 = $row['agent_id'];
					for($i=0; $i<5; $i++){
						$sql_sssl = " SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$agent_id_1." ";
						$res_sssl = mysqli_query($conn, $sql_sssl);
						$row_sssl = mysqli_fetch_array($res_sssl);
						if($row_sssl['parent_id'] != 2){
							$sql_uuul = " UPDATE `v_parent_node` SET `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level." WHERE `pk_id` = ".$row_sssl['parent_id']." ";
							mysqli_query($conn, $sql_uuul);
							$agent_id_1 = $row_sssl['parent_id'];
						}
						$agent_id_1 = $row_sssl['parent_id'];
					}
				}else if($row['t_status'] == 2){
					// 월렛 카드 결제
					// 0. 나의 정보 수정하기
					$sql_p_s = " SELECT `parent_id`,`my_level`,`support_step` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
					$res_p_s = mysqli_query($conn, $sql_p_s);
					$row_p_s = mysqli_fetch_array($res_p_s);
					$start_level = 0;
					$start_s_level = 0;
					if($row['product_price'] == 330){ $start_level = 1; $start_s_level = 30; }
					else if($row['product_price'] == 1100){ $start_level = 2; $start_s_level = 100; }
					else if($row['product_price'] == 3300){ $start_level = 3; $start_s_level = 300; }
					else if($row['product_price'] == 11000){ $start_level = 4; $start_s_level = 1000; }
					else if($row['product_price'] == 33000){ $start_level = 5; $start_s_level = 3000; }
					else{ $start_level = 1; $start_s_level = 1; }

					if($start_level > $row_p_s['my_level']){
						$sql_p_u = " UPDATE `v_parent_node` SET `payment_status` = 1, `my_level` = ".$start_level.", `my_money` = `my_money` + ".$row['product_price'].", `update_time` = '".$time."', `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level.", `support_money_tow_2` = `support_money_tow_2` + ".$start_s_level." WHERE `pk_id` = ".$row['agent_id']." ";
					}else{
						$sql_p_u = " UPDATE `v_parent_node` SET `payment_status` = 1, `my_money` = `my_money` + ".$row['product_price'].", `update_time` = '".$time."', `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level.", `support_money_tow_2` = `support_money_tow_2` + ".$start_s_level." WHERE `pk_id` = ".$row['agent_id']." ";
					}
					mysqli_query($conn, $sql_p_u);
					$vat = $row['product_price'] * 10 / 100;
					$sql_i_vat = " INSERT INTO `v_vat_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES (NULL,".$row['agent_id'].",".$vat.",'10','".$time."') ";
					mysqli_query($conn, $sql_i_vat);

					if($row_p_s['parent_id'] != 0){
						// 1. 추천 수당 20% 바로 위 추천인한테
						$pppppp = $row['product_price'] * 15 / 100;
						$pppppp = $row['product_price'] - $pppppp;
						$extra1_percent = $pppppp * 20 / 100;
						$sql_extra1_u = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$extra1_percent." WHERE `agent_id` = ".$row_p_s['parent_id']." ";
						mysqli_query($conn, $sql_extra1_u);
						$sql_extra1_i = " INSERT INTO `v_parent_bonus` (`pk_id`,`agent_id`,`parent_id`,`parent_pax`,`percent`,`insert_time`) VALUES ";
						$sql_extra1_i .= " (NULL,".$row_p_s['parent_id'].",".$row['agent_id'].",".$extra1_percent.",'20','".$time."') ";
						mysqli_query($conn, $sql_extra1_i);
						$sql_pp_1 = " SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$row_p_s['parent_id']." ";
						$res_pp_1 = mysqli_query($conn, $sql_pp_1);
						$row_pp1 = mysqli_fetch_array($res_pp_1);
						$sql_pp_2 = " SELECT `parent_id`,`support_money_tow_1` FROM `v_parent_node` WHERE `pk_id` = ".$row_pp1['parent_id']." ";
						$res_pp_2 = mysqli_query($conn, $sql_pp_2);
						$row_pp_2 = mysqli_fetch_array($res_pp_2);
						$oooo = 0;
						$iiii = 0;
						if($row_pp_2['support_money_tow_1'] > 10000000){ $oooo = $extra1_percent * 4.0 / 100; $iiii = 4.0; }
						else if($row_pp_2['support_money_tow_1'] > 3000000){ $oooo = $extra1_percent * 3.0 / 100; $iiii = 3.0; }
						else if($row_pp_2['support_money_tow_1'] > 750000){ $oooo = $extra1_percent * 2.0 / 100; $iiii = 2.0; }
						else if($row_pp_2['support_money_tow_1'] > 125000){ $oooo = $extra1_percent * 1.2 / 100; $iiii = 1.2; }
						else if($row_pp_2['support_money_tow_1'] > 25000){ $oooo = $extra1_percent * 1.0 / 100; $iiii = 1.0; }
						else if($row_pp_2['support_money_tow_1'] > 5000){ $oooo = $extra1_percent * 0.8 / 100; $iiii = 0.8; }
						else if($row_pp_2['support_money_tow_1'] > 1000){ $oooo = $extra1_percent * 0.5 / 100; $iiii = 0.5; }
						if($row_pp_2['support_money_tow_1'] > 1000){
							$sql_ooo_u_1 = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$oooo." WHERE `agent_id` = ".$row_pp1['parent_id']." ";
							mysqli_query($conn, $sql_ooo_u_1);
							$sql_ooo_i_1 = " INSERT INTO `v_company_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES (NULL,".$row_pp1['parent_id'].",".$oooo.",'".$iiii."','".$time."') ";
							mysqli_query($conn, $sql_ooo_i_1);
						}
						// 2. 추천 매칭 수당 10% 추천인 기준 2대
						$sql_extra2_s = " SELECT (SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$row_p_s['parent_id'].") AS `parent_1_id` ";
						$sql_extra2_s .= " FROM `v_parent_node` LIMIT 1 ";
						$res_extra2_s = mysqli_query($conn, $sql_extra2_s);
						$row_extra2_s = mysqli_fetch_array($res_extra2_s);
						$extra2_percent = $pppppp * 10 / 100;
						$sql_extra2_u = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$extra2_percent." WHERE `agent_id` IN ( ";
						$sql_extra2_u .= " ".$row_p_s['parent_id'].",".$row_extra2_s['parent_1_id'].") ";
						mysqli_query($conn, $sql_extra2_u);
						$sql_extra2_i = " INSERT INTO `v_parent_matching_bonus` (`pk_id`,`agent_id`,`parent_id`,`parent_pax`,`percent`,`insert_time`) VALUES ";
						$sql_extra2_i .= "(NULL,".$row_p_s['parent_id'].",".$row['agent_id'].",".$extra2_percent.",'10','".$time."'),";
						$sql_extra2_i .= "(NULL,".$row_extra2_s['parent_1_id'].",".$row['agent_id'].",".$extra2_percent.",'10','".$time."')";
						mysqli_query($conn, $sql_extra2_i);

						if($row_pp_2['support_money_tow_1'] > 10000000){ $oooo = $extra2_percent * 4.0 / 100; $iiii = 4.0; }
						else if($row_pp_2['support_money_tow_1'] > 3000000){ $oooo = $extra2_percent * 3.0 / 100; $iiii = 3.0; }
						else if($row_pp_2['support_money_tow_1'] > 750000){ $oooo = $extra2_percent * 2.0 / 100; $iiii = 2.0; }
						else if($row_pp_2['support_money_tow_1'] > 125000){ $oooo = $extra2_percent * 1.2 / 100; $iiii = 1.2; }
						else if($row_pp_2['support_money_tow_1'] > 25000){ $oooo = $extra2_percent * 1.0 / 100; $iiii = 1.0; }
						else if($row_pp_2['support_money_tow_1'] > 5000){ $oooo = $extra2_percent * 0.8 / 100; $iiii = 0.8; }
						else if($row_pp_2['support_money_tow_1'] > 1000){ $oooo = $extra2_percent * 0.5 / 100; $iiii = 0.5; }

						if($row_pp_2['support_money_tow_1'] > 1000){
							$sql_uuuu_u = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$oooo." WHERE `agent_id` = ".$row_pp1['parent_id']." ";
							mysqli_query($conn, $sql_uuuu_u);
							$sql_uuuu_i = " INSERT INTO `v_company_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES ";
							$sql_uuuu_i .= "(NULL,".$row_pp1['parent_id'].",".$oooo.",'".$iiii."','".$time."')";
							mysqli_query($conn, $sql_uuuu_i);
						}

						$sql_uuuu_s = " SELECT `parent_id`,`support_money_tow_1` FROM `v_parent_node` WHERE `pk_id` = ".$row_pp_2['parent_id']." ";
						$res_uuuu_s = mysqli_query($conn, $sql_uuuu_s);
						$row_uuuu_s = mysqli_fetch_array($res_uuuu_s);

						if($row_uuuu_s['support_money_tow_1'] > 10000000){ $oooo = $extra2_percent * 4.0 / 100; $iiii = 4.0; }
						else if($row_uuuu_s['support_money_tow_1'] > 3000000){ $oooo = $extra2_percent * 3.0 / 100; $iiii = 3.0; }
						else if($row_uuuu_s['support_money_tow_1'] > 750000){ $oooo = $extra2_percent * 2.0 / 100; $iiii = 2.0; }
						else if($row_uuuu_s['support_money_tow_1'] > 125000){ $oooo = $extra2_percent * 1.2 / 100; $iiii = 1.2; }
						else if($row_uuuu_s['support_money_tow_1'] > 25000){ $oooo = $extra2_percent * 1.0 / 100; $iiii = 1.0; }
						else if($row_uuuu_s['support_money_tow_1'] > 5000){ $oooo = $extra2_percent * 0.8 / 100; $iiii = 0.8; }
						else if($row_uuuu_s['support_money_tow_1'] > 1000){ $oooo = $extra2_percent * 0.5 / 100; $iiii = 0.5; }

						if($row_uuuu_s['support_money_tow_1'] > 1000){
							$sql_uuuu_u = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$oooo." WHERE `agent_id` = ".$row_pp_2['parent_id']." ";
							mysqli_query($conn, $sql_uuuu_u);
							$sql_uuuu_i = " INSERT INTO `v_company_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES ";
							$sql_uuuu_i .= "(NULL,".$row_pp_2['parent_id'].",".$oooo.",'".$iiii."','".$time."')";
							mysqli_query($conn, $sql_uuuu_i);
						}
						// 3. 추천 기준 점수 주기 5대 더해주기
						$agent_id_1 = $row['agent_id'];
						for($i=0; $i<5; $i++){
							$sql_sssl = " SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$agent_id_1." ";
							$res_sssl = mysqli_query($conn, $sql_sssl);
							$row_sssl = mysqli_fetch_array($res_sssl);
							if($row_sssl['parent_id'] != 2){
								$sql_uuul = " UPDATE `v_parent_node` SET `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level." WHERE `pk_id` = ".$row_sssl['parent_id']." ";
								mysqli_query($conn, $sql_uuul);
								$agent_id_1 = $row_sssl['parent_id'];
							}
							$agent_id_1 = $row_sssl['parent_id'];
						}
					}
				}else if($row['t_status'] == 3){
					// 단말기 결제
					// 0. 나의정보 수정하기
					$sql_p_s = " SELECT `parent_id`,`my_level`,`support_step` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
					$res_p_s = mysqli_query($conn, $sql_p_s);
					$row_p_s = mysqli_fetch_array($res_p_s);
					$start_s_level = 0;

					if($row['product_price'] == 330){ $start_s_level = 5; }
					else if($row['product_price'] == 3300){ $start_s_level = 50; }
					else if($row['product_price'] == 33000){ $start_s_level = 500; }
					else if($row['product_price'] == 330000){ $start_s_level = 5000; }
					else{ $start_s_level = 1; }
					$sql_p_u = " UPDATE `v_parent_node` SET `payment_status` = 1, `my_money` = `my_money` + ".$row['product_price'].", `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level.", `support_money_tow_2` = `support_money_tow_2` + ".$start_s_level." WHERE `pk_id` = ".$row['agent_id']." ";
					mysqli_query($conn, $sql_p_u);
					$vat = $row['product_price'] * 10 / 100;
					$sql_i_vat = " INSERT INTO `v_vat_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES (NULL,".$row['agent_id'].",".$vat.",'10','".$time."') ";
					mysqli_query($conn, $sql_i_vat);

					if($row_p_s['parent_id'] != 0){
						// 1. 추천인수당 5% 주기
						$pppppp = $row['product_price'] * 15 / 100;
						$pppppp = $row['product_price'] - $pppppp;
						$extra1_percent = $pppppp * 5 / 100;
						$sql_extra1_u = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$extra1_percent." WHERE `agent_id` = ".$row_p_s['parent_id']." ";
						mysqli_query($conn, $sql_extra1_u);
						$sql_extra1_i = " INSERT INTO `v_parent_bonus` (`pk_id`,`agent_id`,`parent_id`,`parent_pax`,`percent`,`insert_time`) VALUES ";
						$sql_extra1_i .= " (NULL,".$row_p_s['parent_id'].",".$row['agent_id'].",".$extra1_percent.",'5','".$time."') ";
						mysqli_query($conn, $sql_extra1_i);
						$oooo = 0;
						$iiii = 0;
						$sql_pp_1 = " SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$row_p_s['parent_id']." ";
						$res_pp_1 = mysqli_query($conn, $sql_pp_1);
						$row_pp1 = mysqli_fetch_array($res_pp_1);
						$sql_pp_2 = " SELECT `support_money_tow_1` FROM `v_parent_node` WHERE `pk_id` = ".$row_pp1['parent_id']." ";
						$res_pp_2 = mysqli_query($conn, $sql_pp_2);
						$row_pp_2 = mysqli_fetch_array($res_pp_2);
						if($row_pp_2['support_money_tow_1'] > 10000000){ $oooo = $extra1_percent * 4.0 / 100; $iiii = 4.0; }
						else if($row_pp_2['support_money_tow_1'] > 3000000){ $oooo = $extra1_percent * 3.0 / 100; $iiii = 3.0; }
						else if($row_pp_2['support_money_tow_1'] > 750000){ $oooo = $extra1_percent * 2.0 / 100; $iiii = 2.0; }
						else if($row_pp_2['support_money_tow_1'] > 125000){ $oooo = $extra1_percent * 1.2 / 100; $iiii = 1.2; }
						else if($row_pp_2['support_money_tow_1'] > 25000){ $oooo = $extra1_percent * 1.0 / 100; $iiii = 1.0; }
						else if($row_pp_2['support_money_tow_1'] > 5000){ $oooo = $extra1_percent * 0.8 / 100; $iiii = 0.8; }
						else if($row_pp_2['support_money_tow_1'] > 1000){ $oooo = $extra1_percent * 0.5 / 100; $iiii = 0.5; }
						if($row_pp_2['support_money_tow_1'] > 1000){
							$sql_ooo_u_1 = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$oooo." WHERE `agent_id` = ".$row_pp1['parent_id']." ";
							mysqli_query($conn, $sql_ooo_u_1);
							$sql_ooo_i_1 = " INSERT INTO `v_company_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES (NULL,".$row_pp1['parent_id'].",".$oooo.",'".$iiii."','".$time."') ";
							mysqli_query($conn, $sql_ooo_i_1);
						}
						// 2. 추천점수 주기
						$agent_id_1 = $row['agent_id'];
						for($i=0; $i<5; $i++){
							$sql_sssl = " SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$agent_id_1." ";
							$res_sssl = mysqli_query($conn, $sql_sssl);
							$row_sssl = mysqli_fetch_array($res_sssl);
							if($row_sssl['parent_id'] != 2){
								$sql_uuul = " UPDATE `v_parent_node` SET `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level." WHERE `pk_id` = ".$row_sssl['parent_id']." ";
								mysqli_query($conn, $sql_uuul);
								$agent_id_1 = $row_sssl['parent_id'];
							}
							$agent_id_1 = $row_sssl['parent_id'];
						}
					}
				}else if($row['t_status'] == 4){
					// 비자 카드 결제
					// 0. 나의정보 수정하기
					$sql_p_s = " SELECT `parent_id`,`my_level`,`support_step` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
					$res_p_s = mysqli_query($conn, $sql_p_s);
					$row_p_s = mysqli_fetch_array($res_p_s);

					$start_s_level = 0;
					if($row['product_price'] == 55){ $start_s_level = 1; }
					else if($row['product_price'] == 550){ $start_s_level = 10; }
					else if($row['product_price'] == 5500){ $start_s_level = 100; }
					else if($row['product_price'] == 55000){ $start_s_level = 1000; }
					else if($row['product_price'] == 550000){ $start_s_level = 10000; }
					else{ $start_s_level = 1; }

					$sql_p_u = " UPDATE `v_parent_node` SET `payment_status` = 1, `my_money` = `my_money` + ".$row['product_price'].", `update_time` = '".$time."', `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level.", `support_money_tow_2` = `support_money_tow_2` + ".$start_s_level." WHERE `pk_id` = ".$row['agent_id']." ";
					mysqli_query($conn, $sql_p_u);
					$vat = $row['product_price'] * 10 / 100;
					$sql_i_vat = " INSERT INTO `v_vat_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES (NULL,".$row['agent_id'].",".$vat.",'10','".$time."') ";
					mysqli_query($conn, $sql_i_vat);
					if($row_p_s['parent_id'] != 0){
						// 1. 추천수당 10% 주기
						$pppppp = $row['product_price'] * 15 / 100;
						$pppppp = $row['product_price'] - $pppppp;
						$extra1_percent = $pppppp * 10 / 100;
						$sql_extra1_u = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$extra1_percent." WHERE `agent_id` = ".$row_p_s['parent_id']." ";
						mysqli_query($conn, $sql_extra1_u);
						$sql_extra1_i = " INSERT INTO `v_parent_bonus` (`pk_id`,`agent_id`,`parent_id`,`parent_pax`,`percent`,`insert_time`) VALUES ";
						$sql_extra1_i .= " (NULL,".$row_p_s['parent_id'].",".$row['agent_id'].",".$extra1_percent.",'10','".$time."') ";
						mysqli_query($conn, $sql_extra1_i);
						$oooo = 0;
						$iiii = 0;
						$sql_pp_1 = " SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$row_p_s['parent_id']." ";
						$res_pp_1 = mysqli_query($conn, $sql_pp_1);
						$row_pp1 = mysqli_fetch_array($res_pp_1);
						$sql_pp_2 = " SELECT `support_money_tow_1` FROM `v_parent_node` WHERE `pk_id` = ".$row_pp1['parent_id']." ";
						$res_pp_2 = mysqli_query($conn, $sql_pp_2);
						$row_pp_2 = mysqli_fetch_array($res_pp_2);
						if($row_pp_2['support_money_tow_1'] > 10000000){ $oooo = $extra1_percent * 4.0 / 100; $iiii = 4.0; }
						else if($row_pp_2['support_money_tow_1'] > 3000000){ $oooo = $extra1_percent * 3.0 / 100; $iiii = 3.0; }
						else if($row_pp_2['support_money_tow_1'] > 750000){ $oooo = $extra1_percent * 2.0 / 100; $iiii = 2.0; }
						else if($row_pp_2['support_money_tow_1'] > 125000){ $oooo = $extra1_percent * 1.2 / 100; $iiii = 1.2; }
						else if($row_pp_2['support_money_tow_1'] > 25000){ $oooo = $extra1_percent * 1.0 / 100; $iiii = 1.0; }
						else if($row_pp_2['support_money_tow_1'] > 5000){ $oooo = $extra1_percent * 0.8 / 100; $iiii = 0.8; }
						else if($row_pp_2['support_money_tow_1'] > 1000){ $oooo = $extra1_percent * 0.5 / 100; $iiii = 0.5; }
						if($row_pp_2['support_money_tow_1'] > 1000){
							$sql_ooo_u_1 = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$oooo." WHERE `agent_id` = ".$row_pp1['parent_id']." ";
							mysqli_query($conn, $sql_ooo_u_1);
							$sql_ooo_i_1 = " INSERT INTO `v_company_bonus` (`pk_id`,`agent_id`,`parent_pax`,`percent`,`insert_time`) VALUES (NULL,".$row_pp1['parent_id'].",".$oooo.",'".$iiii."','".$time."') ";
							mysqli_query($conn, $sql_ooo_i_1);
						}
						// 2. 추천점수 주기
						$agent_id_1 = $row['agent_id'];
						for($i=0; $i<5; $i++){
							$sql_sssl = " SELECT `parent_id` FROM `v_parent_node` WHERE `pk_id` = ".$agent_id_1." ";
							$res_sssl = mysqli_query($conn, $sql_sssl);
							$row_sssl = mysqli_fetch_array($res_sssl);
							if($row_sssl['parent_id'] != 2){
								$sql_uuul = " UPDATE `v_parent_node` SET `support_money_tow_1` = `support_money_tow_1` + ".$start_s_level." WHERE `pk_id` = ".$row_sssl['parent_id']." ";
								mysqli_query($conn, $sql_uuul);
								$agent_id_1 = $row_sssl['parent_id'];
							}
							$agent_id_1 = $row_sssl['parent_id'];
						}
					}
				}
				echo "SUCCESS";
			}else{ echo "FAIL"; }
		}else{ echo "FAIL"; }
	}

	if($result == 'a_login'){
		if($conn){
			$user_id = $_POST['user_id'];
			$password = mysql_new_password($_POST['password']);

			$sql_select = " SELECT `pk_id`,`user_id`,`member_rock` FROM `v_parent_node` WHERE `user_id` = '".$user_id."' AND `user_password` = '".$password."' ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				setcookie("a_cobu_user_id",$user_id,0,"/",".livilcorp.com");
				setcookie("a_cobu_user_password",$password,0,"/",".livilcorp.com");
				setcookie("a_cobu_pk_id",$row['pk_id'],0,"/",".livilcorp.com");
				setcookie("a_cobu_member_rock",$row['member_rock'],0,"/",".livilcorp.com");

				$arr = array(
					"code" => 1,
					"id" => $row['user_id'],
					"message" => "SUCC"
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
	}else if($result == 'u_login'){
		if($conn){
			$user_id = $_POST['user_id'];
			$password = mysql_new_password($_POST['password']);

			$sql_select = " SELECT `pk_id`,`member_rock` FROM `v_parent_node` WHERE `user_id` = '".$user_id."' AND `user_password` = '".$password."' ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				if($row['member_rock'] == 1){
					setcookie("u_cobu_user_id",$user_id,0,"/",".livilcorp.com");
					setcookie("u_cobu_user_password",$password,0,"/",".livilcorp.com");
					setcookie("u_cobu_pk_id",$row['pk_id'],0,"/",".livilcorp.com");

					$arr = array(
						"code" => 1,
						"message" => "SUCC"
					);
				}else{
					$sql_count = " SELECT COUNT(*) AS `count` FROM `v_parent_node` WHERE `parent_id` = ".$row['pk_id']." AND `member_rock` = 1 ";
					$res_count = mysqli_query($conn, $sql_count);
					$row_count = mysqli_fetch_array($res_count);
					if($row_count['count'] > 4){
						$sql_update = " UPDATE `v_parent_node` SET `member_rock` = 1 WHERE `pk_id` = ".$row['pk_id']." ";
						mysqli_query($conn, $sql_update);
						setcookie("u_cobu_user_id",$user_id,0,"/",".livilcorp.com");
						setcookie("u_cobu_user_password",$password,0,"/",".livilcorp.com");
						setcookie("u_cobu_pk_id",$row['pk_id'],0,"/",".livilcorp.com");

						$arr = array(
							"code" => 1,
							"message" => "SUCC"
						);
					}else{
						$arr = array(
							"code" => 0,
							"pk_id" => $row['pk_id'],
							"message" => "MI"
						);
					}
				}
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
	}else if($result == 'u_logout'){
		setcookie("u_cobu_user_id",null,-1,"/",".livilcorp.com");
		setcookie("u_cobu_user_password",null,-1,"/",".livilcorp.com");
		setcookie("u_cobu_pk_id",null,-1,"/",".livilcorp.com");

		$arr = array(
			"code" => 1,
			"message" => "SUCC"
		);
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_ck_cookie'){
		if($conn){
			$sql_select = " SELECT `pk_id`,`user_id`,`p_uid`,`otp_sercet`,`support_money_tow_1`,`user_status` FROM `v_parent_node` WHERE `user_id` = '".$_COOKIE["u_cobu_user_id"]."' AND `user_password` = '".$_COOKIE["u_cobu_user_password"]."' AND `member_rock` = 1 ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);
			if($row['pk_id']){
				$res = array(
					"pk_id" => $row['pk_id'],
					"user_id" => $row['user_id'],
					"p_uid" => $row['p_uid'],
					"otp_sercet" => $row['otp_sercet'],
					"support_money_tow_1" => $row['support_money_tow_1'],
					"user_status" => $row['user_status']
				);
				$res = json_encode($res, JSON_UNESCAPED_UNICODE);
				$arr = array(
					"code" => 1,
					"message" => $res
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
	}else if($result == 'u_support_list'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$sql_select = " SELECT `pk_id`,`parent_id`,`support_id`,`user_name`,`user_id`,`user_mobile`,`member_rock`,`insert_time`,`update_time` FROM `v_parent_node` WHERE `support_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);

			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"parent_id" => $row['parent_id'],
					"support_id" => $row['support_id'],
					"user_name" => $row['user_name'],
					"user_id" => $row['user_id'],
					"user_mobile" => $row['user_mobile'],
					"member_rock" => $row['member_rock'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_support_genealogy'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];

			$sql_select = " SELECT `pk_id`,`user_id`,`support_id_1`,`support_id_2`,`line`,`left_level`,`right_level`,`member_rock` FROM `v_parent_node` WHERE `support_id` = ".$j_pk_id." ORDER BY `line` ASC ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"user_id" => $row['user_id'],
					"support_id_1" => $row['support_id_1'],
					"support_id_2" => $row['support_id_2'],
					"line" => $row['line'],
					"support_1" => $row['left_level'],
					"support_2" => $row['right_level'],
					"member_rock" => $row['member_rock']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_clickuser_id'){
		if($conn){
			$user_id = $_POST['user_id'];

			$sql_select = " SELECT `user_id` FROM `v_parent_node` WHERE `user_id` = '".$user_id."' ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['user_id']){
				$arr = array(
					"code" => 0,
					"message" => "NO"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => "SUCC"
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
	}else if($result == 'u_toRegistered'){
		if($conn){
			$user_name = $_POST['user_name']; 
			$user_id = $_POST['user_id'];
			$user_phon = $_POST['user_phon'];
			$j_pk_id = $_POST['j_pk_id'];
			$password = mysql_new_password('1234');

			$sql_s_p = " SELECT `parent_count`,`parent_step` FROM `v_parent_node` WHERE `pk_id` = ".$j_pk_id." ";
			$res_s_p = mysqli_query($conn, $sql_s_p);
			$row_s_p = mysqli_fetch_array($res_s_p);
			$parent_step = $row_s_p['parent_step'] + 1;
			$time = time();

			$sql_insert = " INSERT INTO `v_parent_node` (`pk_id`,`parent_id`,`support_id`,`user_name`,`user_id`,`user_mobile`,`user_password`,`p_uid`,`parent_count`,`support_id_1`,`support_id_2`,`payment_status`,";
			$sql_insert .= "`my_level`,`support_money_one_1`,`support_money_one_2`,`left_level`,`right_level`,`support_step`,`parent_step`,`otp_sercet`,`member_rock`,`line`,`insert_time`,`update_time`) VALUES ";
			$sql_insert .= " (NULL,".$j_pk_id.",0,'".$user_name."','".$user_id."','".$user_phon."','".$password."',0,0,0,0,0, ";
			$sql_insert .= " 0,0,0,0,0,0,".$parent_step.",'0',0,0,'".$time."','".$time."') ";
			mysqli_query($conn, $sql_insert);

			$sql_select = " SELECT `pk_id` FROM `v_parent_node` WHERE `user_id` = '".$user_id."' ";
			$res_s_b = mysqli_query($conn, $sql_select);
			$row_s_b = mysqli_fetch_array($res_s_b);

			$sql_i_wallet = " INSERT INTO `v_coin_wallet` (`pk_id`,`agent_id`,`parent_id`,`support_id`,`user_name`,`user_id`,`user_mobile`,`parent_pax`,`parent_csp`,`withdraw_pax`,`insert_time`,`update_time`,`daily_time`,`status`) VALUES ";
			$sql_i_wallet .= " (NULL,".$row_s_b['pk_id'].",".$j_pk_id.",0,'".$user_name."','".$user_id."','".$user_phon."',0,0,0,'".$time."','".$time."','".$time."',1) ";
			mysqli_query($conn, $sql_i_wallet);

			$sql_u_p = " UPDATE `v_parent_node` SET `parent_count` = `parent_count` + 1 WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_u_p);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_payment_ck'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` = ".$pk_id." AND `member_rock` = 1 ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				$arr = array(
					"code" => 0,
					"message" => "YOU"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => "SUCC"
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
	}else if($result == 'u_support_details'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_select = " SELECT `pk_id`,`parent_id`,`support_id`,`user_name`,`user_id`,`user_mobile`,`member_rock`,`insert_time`,`update_time` FROM `v_parent_node` WHERE `support_id` = ".$j_pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"parent_id" => $row['parent_id'],
					"support_id" => $row['support_id'],
					"user_name" => $row['user_name'],
					"user_id" => $row['user_id'],
					"user_mobile" => $row['user_mobile'],
					"member_rock" => $row['member_rock'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_parent_count'){
		if($conn){
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_mobile = $_POST['s_user_mobile'];
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_parent_node` WHERE `parent_id` = ".$pk_id." ";
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_id != ""){ $sql_select .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_mobile != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_mobile."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);
			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $row['count']
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
	}else if($result == 'u_parent'){
		if($conn){
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_mobile = $_POST['s_user_mobile'];
			$pk_id = $_POST['pk_id'];
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT `pk_id`,`user_name`,`user_id`,`user_mobile`,`member_rock`,`insert_time`,`update_time` FROM `v_parent_node` WHERE `parent_id` = ".$pk_id." ";
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_id != ""){ $sql_select .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_mobile != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_mobile."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"user_name" => $row['user_name'],
					"user_id" => $row['user_id'],
					"user_mobile" => $row['user_mobile'],
					"member_rock" => $row['member_rock'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_parent_genealogy'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_select = " SELECT `pk_id`,`user_id`,`support_money_tow_1`,`member_rock` FROM `v_parent_node` WHERE `parent_id` = ".$j_pk_id." ORDER BY `pk_id` ASC ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"user_id" => $row['user_id'],
					"support_money_tow_1" => $row['support_money_tow_1'],
					"member_rock" => $row['member_rock']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_wallet'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT * FROM `v_coin_wallet` WHERE `agent_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_s_w = " SELECT `status` FROM `v_withdraw_apply` WHERE `agent_id` = ".$pk_id." AND `status` = 0 ";
			$res_s_w = mysqli_query($conn, $sql_s_w);
			$row_s_w = mysqli_fetch_array($res_s_w);

			$res = array(
				"pk_id" => $row['pk_id'],
				"agent_id" => $row['agent_id'],
				"user_name" => $row['user_name'],
				"user_id" => $row['user_id'],
				"user_mobile" => $row['user_mobile'],
				"parent_pax" => $row['parent_pax'],
				"parent_csp" => $row['parent_csp'],
				"withdraw_pax" => $row['withdraw_pax'],
				"insert_time" => $row['insert_time'],
				"update_time" => $row['update_time'],
				"daily_time" => $row['daily_time'],
				"status" => $row['status'],
				"status_w" => ($row_s_w['status']==null?5:$row_s_w['status'])
			);
			$res = json_encode($res, JSON_UNESCAPED_UNICODE);
			$arr = array(
				"code" => 1,
				"message" => $res
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_join_bonus_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_join_bonus` WHERE `agent_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_join_bonus` WHERE `agent_id` = ".$pk_id." ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);
			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => $row_sum['parent_pax'],
					"message" => $row['count']
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
	}else if($result == 'u_join_bonus'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_join_bonus` WHERE `agent_id` = ".$pk_id." ";
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s_p = " SELECT `user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['parent_id']." ";
				$res_s_p = mysqli_query($conn, $sql_s_p);
				$row_s_p = mysqli_fetch_array($res_s_p);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"parent_id" => $row['parent_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time'],
					"user_id" => $row_s_p['user_id']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_parent_bonus_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_parent_bonus` WHERE `agent_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_parent_bonus` WHERE `agent_id` = ".$pk_id." ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);
			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => $row_sum['parent_pax'],
					"message" => $row['count']
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
	}else if($result == 'u_parent_bonus'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_parent_bonus` WHERE `agent_id` = ".$pk_id." ";
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s_p = " SELECT `user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['parent_id']." ";
				$res_s_p = mysqli_query($conn, $sql_s_p);
				$row_s_p = mysqli_fetch_array($res_s_p);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"parent_id" => $row['parent_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time'],
					"user_id" => $row_s_p['user_id']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_parent_matching_bonus_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_parent_matching_bonus` WHERE `agent_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_parent_matching_bonus` WHERE `agent_id` = ".$pk_id." ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);
			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => $row_sum['parent_pax'],
					"message" => $row['count']
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
	}else if($result == 'u_parent_matching_bonus'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_parent_matching_bonus` WHERE `agent_id` = ".$pk_id." ";
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s_p = " SELECT `user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['parent_id']." ";
				$res_s_p = mysqli_query($conn, $sql_s_p);
				$row_s_p = mysqli_fetch_array($res_s_p);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"parent_id" => $row['parent_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time'],
					"user_id" => $row_s_p['user_id']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_ck_cookie'){
		if($conn){
			$sql_select = " SELECT `pk_id` FROM `v_parent_node` WHERE `user_id` = '".$_COOKIE["a_cobu_user_id"]."' AND `user_password` = '".$_COOKIE["a_cobu_user_password"]."' ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);
			if($row['pk_id']){
				$res = array(
					"pk_id" => $row['pk_id']
				);
				$res = json_encode($res, JSON_UNESCAPED_UNICODE);
				$arr = array(
					"code" => 1,
					"message" => $res
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
	}else if($result == 'a_logout'){
		setcookie("a_cobu_user_id",null,-1,"/",".livilcorp.com");
		setcookie("a_cobu_user_password",null,-1,"/",".livilcorp.com");
		setcookie("a_cobu_pk_id",null,-1,"/",".livilcorp.com");

		$arr = array(
			"code" => 1,
			"message" => "SUCC"
		);
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_member_count'){
		if($conn){
			$s_branch_id = $_POST['s_branch_id'];
			$s_pk_id = $_POST['s_pk_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_parent_node` WHERE `pk_id` != 1 AND `pk_id` != 2 ";
			if($s_branch_id != ""){ $sql_select .= " AND `branch_settings` = '".$s_branch_id."' "; }
			if($s_pk_id != ""){ $sql_select .= " AND `pk_id` = ".$s_pk_id." "; }
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_id != ""){ $sql_select .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_phon != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_phon."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $row['count']
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
	}else if($result == 'a_member'){
		if($conn){
			$s_branch_id = $_POST['s_branch_id'];
			$s_pk_id = $_POST['s_pk_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_parent_node` WHERE `pk_id` != 1 AND `pk_id` != 2 ";
			if($s_branch_id != ""){ $sql_select .= " AND `branch_settings` = '".$s_branch_id."' "; }
			if($s_pk_id != ""){ $sql_select .= " AND `pk_id` = ".$s_pk_id." "; }
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_id != ""){ $sql_select .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_phon != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_phon."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);

			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"parent_id" => $row['parent_id'],
					"user_name" => $row['user_name'],
					"user_id" => $row['user_id'],
					"user_mobile" => $row['user_mobile'],
					"p_uid" => $row['p_uid'],
					"parent_count" => $row['parent_count'],
					"payment_status" => $row['payment_status'],
					"support_money_tow_1" => $row['support_money_tow_1'],
					"support_money_tow_2" => $row['support_money_tow_2'],
					"otp_sercet" => $row['otp_sercet'],
					"insert_time" => $row['insert_time'],
					"user_status" => $row['user_status'],
					"idcard_id" => $row['idcard_id'],
					"branch_settings" => $row['branch_settings'],
					"memolan" => $row['memolan']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_parent_details_count'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$s_pk_id = $_POST['s_pk_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_parent_node` WHERE `parent_id` = ".$j_pk_id." ";
			if($s_pk_id != ""){ $sql_select .= " AND `pk_id` = ".$s_pk_id." "; }
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_id != ""){ $sql_select .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_phon != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_phon."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $row['count']
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
	}else if($result == 'a_parent_details'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$s_pk_id = $_POST['s_pk_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_parent_node` WHERE `parent_id` = ".$j_pk_id." ";
			if($s_pk_id != ""){ $sql_select .= " AND `pk_id` = ".$s_pk_id." "; }
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_id != ""){ $sql_select .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_phon != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_phon."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);

			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"parent_id" => $row['parent_id'],
					"user_name" => $row['user_name'],
					"user_id" => $row['user_id'],
					"user_mobile" => $row['user_mobile'],
					"p_uid" => $row['p_uid'],
					"parent_count" => $row['parent_count'],
					"payment_status" => $row['payment_status'],
					"support_money_tow_1" => $row['support_money_tow_1'],
					"support_money_tow_2" => $row['support_money_tow_2'],
					"otp_sercet" => $row['otp_sercet'],
					"insert_time" => $row['insert_time'],
					"user_status" => $row['user_status']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_support_details'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];

			$sql_select = " SELECT * FROM `v_parent_node` WHERE `support_id` = ".$j_pk_id." ORDER BY `line` ASC ";
			$result = mysqli_query($conn, $sql_select);

			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"parent_id" => $row['parent_id'],
					"support_id" => $row['support_id'],
					"user_name" => $row['user_name'],
					"user_id" => $row['user_id'],
					"user_mobile" => $row['user_mobile'],
					"p_uid" => $row['p_uid'],
					"parent_count" => $row['parent_count'],
					"support_id_1" => $row['support_id_1'],
					"support_id_2" => $row['support_id_2'],
					"payment_status" => $row['payment_status'],
					"support_money_one_1" => $row['support_money_one_1'],
					"support_money_one_2" => $row['support_money_one_2'],
					"left_level" => $row['left_level'],
					"right_level" => $row['right_level'],
					"otp_sercet" => $row['otp_sercet'],
					"insert_time" => $row['insert_time'],
					"user_status" => $row['user_status']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_information_action'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$m_user_name = $_POST['m_user_name'];
			$m_user_mobile = $_POST['m_user_mobile'];

			$sql_update = " UPDATE `v_parent_node` SET `user_name` = '".$m_user_name."', `user_mobile` = '".$m_user_mobile."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);

			$sql_u_w = " UPDATE `v_coin_wallet` SET `user_name` = '".$m_user_name."', `user_mobile` = '".$m_user_mobile."' WHERE `agent_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_u_w);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_reset_password'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$password = mysql_new_password('1234');

			$sql_update = " UPDATE `v_parent_node` SET `user_password` = '".$password."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_reset_otp'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];

			$sql_update = " UPDATE `v_parent_node` SET `otp_sercet` = '0' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_reset_paybank'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];

			$sql_update = " UPDATE `v_parent_node` SET `p_uid` = 0 WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_assets_list'){
		if($conn){
			$sql_select = " SELECT ";
			$sql_select .= " (SELECT SUM(`parent_pax`) FROM `v_coin_wallet`) AS `parent_pax`, ";
			$sql_select .= " (SELECT SUM(`parent_csp`) FROM `v_coin_wallet`) AS `parent_csp`, ";
			$sql_select .= " (SELECT SUM(`withdraw_pax`) FROM `v_coin_wallet`) AS `withdraw_pax` ";
			$sql_select .= " FROM `v_coin_wallet` LIMIT 1 ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$res = array(
				"parent_pax" => $row['parent_pax'],
				"parent_csp" => $row['parent_csp'],
				"withdraw_pax" => $row['withdraw_pax']
			);
			$res = json_encode($res, JSON_UNESCAPED_UNICODE);
			if($res == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res
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
	}else if($result == 'a_wallet_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_phon = $_POST['s_user_phon'];

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_coin_wallet` WHERE `pk_id` != 2 ";
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = '".$s_agent_id."' "; }
			if($s_user_id != ""){ $sql_select .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_phon != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_phon."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT ";
			$sql_sum .= " (SELECT SUM(`parent_pax`) FROM `v_coin_wallet` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = '".$s_agent_id."' "; }
			if($s_user_id != ""){ $sql_sum .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_name != ""){ $sql_sum .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_phon != ""){ $sql_sum .= " AND `user_mobile` = '".$s_user_phon."' "; }
			$sql_sum .= " ) AS `parent_pax`, ";
			$sql_sum .= " (SELECT SUM(`parent_csp`) FROM `v_coin_wallet` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = '".$s_agent_id."' "; }
			if($s_user_id != ""){ $sql_sum .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_name != ""){ $sql_sum .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_phon != ""){ $sql_sum .= " AND `user_mobile` = '".$s_user_phon."' "; }
			$sql_sum .= " ) AS `parent_csp`, ";
			$sql_sum .= " (SELECT SUM(`withdraw_pax`) FROM `v_coin_wallet` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = '".$s_agent_id."' "; }
			if($s_user_id != ""){ $sql_sum .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_name != ""){ $sql_sum .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_phon != ""){ $sql_sum .= " AND `user_mobile` = '".$s_user_phon."' "; }
			$sql_sum .= " ) AS `withdraw_pax` ";
			$sql_sum .= " FROM `v_coin_wallet` LIMIT 1 ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => $row_sum['parent_pax'],
					"parent_csp" => $row_sum['parent_csp'],
					"withdraw_pax" => $row_sum['withdraw_pax'],
					"message" => $row['count']
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
	}else if($result == 'a_wallet'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_coin_wallet` WHERE `pk_id` != 2 ";
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = '".$s_agent_id."' "; }
			if($s_user_id != ""){ $sql_select .= " AND `user_id` = '".$s_user_id."' "; }
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_phon != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_phon."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);

			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_id" => $row['user_id'],
					"user_name" => $row['user_name'],
					"user_mobile" => $row['user_mobile'],
					"parent_pax" => $row['parent_pax'],
					"parent_csp" => $row['parent_csp'],
					"withdraw_pax" => $row['withdraw_pax'],
					"update_time" => $row['update_time'],
					"daily_time" => $row['daily_time'],
					"memo_lan" => $row['memo_lan']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_join_bonus_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$parent_id = 0;
			
			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_join_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != "" || $s_user_name != "" || $s_user_phon != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				if($s_user_name != ""){ $sql_s .= " AND `user_name` = '".$s_user_name."' "; }
				if($s_user_phon != ""){ $sql_s .= " AND `user_mobile` = '".$s_user_phon."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT ";
			$sql_sum .= " (SELECT SUM(`parent_pax`) FROM `v_join_bonus` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_sum .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_sum .= " ) AS `parent_pax` ";
			$sql_sum .= " FROM `v_join_bonus` LIMIT 1 ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_join_bonus'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];
			$parent_id = 0;

			$sql_select = " SELECT * FROM `v_join_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != "" || $s_user_name != "" || $s_user_phon != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				if($s_user_name != ""){ $sql_s .= " AND `user_name` = '".$s_user_name."' "; }
				if($s_user_phon != ""){ $sql_s .= " AND `user_mobile` = '".$s_user_phon."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);

				$sql_ss = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['parent_id']." ";
				$res_ss = mysqli_query($conn, $sql_ss);
				$row_ss = mysqli_fetch_array($res_ss);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_s['user_name'],
					"user_id" => $row_s['user_id'],
					"payer_name" => $row_ss['user_name'],
					"payer_id" => $row_ss['user_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_parent_bonus_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$parent_id = 0;

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_parent_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != "" || $s_user_name != "" || $s_user_phon != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				if($s_user_name != ""){ $sql_s .= " AND `user_name` = '".$s_user_name."' "; }
				if($s_user_phon != ""){ $sql_s .= " AND `user_mobile` = '".$s_user_phon."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT ";
			$sql_sum .= " (SELECT SUM(`parent_pax`) FROM `v_parent_bonus` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_sum .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_sum .= " ) AS `parent_pax` ";
			$sql_sum .= " FROM `v_parent_bonus` LIMIT 1 ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_parent_bonus'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];
			$parent_id = 0;

			$sql_select = " SELECT * FROM `v_parent_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != "" || $s_user_name != "" || $s_user_phon != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				if($s_user_name != ""){ $sql_s .= " AND `user_name` = '".$s_user_name."' "; }
				if($s_user_phon != ""){ $sql_s .= " AND `user_mobile` = '".$s_user_phon."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);

				$sql_ss = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['parent_id']." ";
				$res_ss = mysqli_query($conn, $sql_ss);
				$row_ss = mysqli_fetch_array($res_ss);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_s['user_name'],
					"user_id" => $row_s['user_id'],
					"payer_name" => $row_ss['user_name'],
					"payer_id" => $row_ss['user_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_parent_mat_bonus_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$parent_id = 0;
			
			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_parent_matching_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != "" || $s_user_name != "" || $s_user_phon != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				if($s_user_name != ""){ $sql_s .= " AND `user_name` = '".$s_user_name."' "; }
				if($s_user_phon != ""){ $sql_s .= " AND `user_mobile` = '".$s_user_phon."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT ";
			$sql_sum .= " (SELECT SUM(`parent_pax`) FROM `v_parent_matching_bonus` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_sum .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_sum .= " ) AS `parent_pax` ";
			$sql_sum .= " FROM `v_parent_matching_bonus` LIMIT 1 ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_parent_mat_bonus'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];
			$parent_id = 0;

			$sql_select = " SELECT * FROM `v_parent_matching_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != "" || $s_user_name != "" || $s_user_phon != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				if($s_user_name != ""){ $sql_s .= " AND `user_name` = '".$s_user_name."' "; }
				if($s_user_phon != ""){ $sql_s .= " AND `user_mobile` = '".$s_user_phon."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);

				$sql_ss = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['parent_id']." ";
				$res_ss = mysqli_query($conn, $sql_ss);
				$row_ss = mysqli_fetch_array($res_ss);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_s['user_name'],
					"user_id" => $row_s['user_id'],
					"payer_name" => $row_ss['user_name'],
					"payer_id" => $row_ss['user_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_order_history_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_status = $_POST['s_status'];
			$s_t_status = $_POST['s_t_status'];

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_login_payment` WHERE `agent_id` = ".$pk_id." ";
			if($s_status != 99 && $s_status != ""){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_t_status != 0 && $s_t_status != ""){ $sql_select .= " AND `t_status` = ".$s_t_status." "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);
			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $row['count'] 
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
	}else if($result == 'u_order_history'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_status = $_POST['s_status'];
			$s_t_status = $_POST['s_t_status'];
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_login_payment` WHERE `agent_id` = ".$pk_id." ";
			if($s_status != 99 && $s_status != ""){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_t_status != 0 && $s_t_status != ""){ $sql_select .= " AND `t_status` = ".$s_t_status." "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row['user_name'],
					"user_id" => $row['user_id'],
					"user_mobile" => $row['user_mobile'],
					"product_price" => $row['product_price'],
					"order_num" => $row['order_num'],
					"status" => $row['status'],
					"t_status" => $row['t_status'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time'],
					"receiving_number" => $row['receiving_number'],
					"receiving_address" => $row['receiving_address'],
					"color_id" => $row['color_id']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_marketing'){
		if($conn){
			$sql_select = " SELECT * FROM `v_marketing` WHERE `status` = 'A' ORDER BY `pk_id` DESC ";
			$result = mysqli_query($conn, $sql_select);
			strip_tags();

			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"title" => $row['title'],
					"content" => $row['content'],
					"content_2" => strip_tags($row['content']),
					"insert_time" => $row['insert_time'],
					"status" => $row['status'],
					"img_path" => $row['img_path'],
					"zip_path" => $row['zip_path']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_select_information'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT `user_name`,`user_id`,`user_mobile`,`parent_count`,`support_money_tow_1`,`support_money_tow_2` FROM `v_parent_node` WHERE `pk_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$arr = array(
				"code" => 1,
				"user_name" => $row['user_name'],
				"user_id" => $row['user_id'],
				"user_mobile" => $row['user_mobile'],
				"parent_count" => $row['parent_count'],
				"support_money_tow_1" => $row['support_money_tow_1'],
				"support_money_tow_2" => $row['support_money_tow_2']
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_information_action'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$m_user_name = $_POST['m_user_name'];
			$m_user_mobile = $_POST['m_user_mobile'];

			$sql_update = " UPDATE `v_parent_node` SET `user_name` = '".$m_user_name."',`user_mobile` = '".$m_user_mobile."' WHERE `pk_id` = ".$pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_password_action'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$m_o_password = mysql_new_password($_POST['m_o_password']);
			$m_n_password = mysql_new_password($_POST['m_n_password']);

			$sql_select = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` = ".$pk_id." AND `user_password` = '".$m_o_password."' ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				$sql_update = " UPDATE `v_parent_node` SET `user_password` = '".$m_n_password."' WHERE `pk_id` = ".$pk_id." ";
				mysqli_query($conn, $sql_update);

				$arr = array(
					"code" => 1,
					"message" => "SUCC"
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
	}else if($result == 'a_order_history_count'){
		if($conn){
			$s_status = $_POST['s_status'];
			$s_t_status = $_POST['s_t_status'];
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_phone = $_POST['s_user_phone'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_login_payment` WHERE `pk_id` != '' ";
			if($s_status != 99 && $s_status != ""){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_t_status != 0 && $s_t_status != ""){ $sql_select .= " AND `t_status` = ".$s_t_status." "; }
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_phone != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_phone."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`product_price`) AS `product_price` FROM `v_login_payment` WHERE `pk_id` != '' ";
			if($s_status != 99 && $s_status != ""){ $sql_sum .= " AND `status` = ".$s_status." "; }
			if($s_t_status != 0 && $s_t_status != ""){ $sql_sum .= " AND `t_status` = ".$s_t_status." "; }
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_user_name != ""){ $sql_sum .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_phone != ""){ $sql_sum .= " AND `user_mobile` = '".$s_user_phone."' "; }
			if($s_start_date != ""){ $sql_sum .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `update_time` < '".$s_stop_date."' "; }
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"product_price" => $row_sum['product_price'],
					"message" => $row['count'] 
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
	}else if($result == 'a_order_history'){
		if($conn){
			$i_count = 0;
			$s_status = $_POST['s_status'];
			$s_t_status = $_POST['s_t_status'];
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_phone = $_POST['s_user_phone'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_login_payment` WHERE `pk_id` != '' ";
			if($s_status != 99 && $s_status != ""){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_t_status != 0 && $s_t_status != ""){ $sql_select .= " AND `t_status` = ".$s_t_status." "; }
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_user_name != ""){ $sql_select .= " AND `user_name` = '".$s_user_name."' "; }
			if($s_user_phone != ""){ $sql_select .= " AND `user_mobile` = '".$s_user_phone."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);

			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row['user_name'],
					"user_id" => $row['user_id'],
					"user_mobile" => $row['user_mobile'],
					"product_price" => $row['product_price'],
					"order_num" => $row['order_num'],
					"status" => $row['status'],
					"t_status" => $row['t_status'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time'],
					"receiving_number" => $row['receiving_number'],
					"receiving_address" => $row['receiving_address'],
					"color_id" => $row['color_id'],
					"tt_status" => $row['tt_status'],
					"memo_lan" => $row['memo_lan']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_insert_google'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$data = google_otp($pk_id);
			$arr = array(
				"code" => 1,
				"message" => $data
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_authenticate'){
		if($conn){
			require_once './class/GoogleAuthenticator.php';
			$pk_id = $_POST['pk_id'];
			$number_id = $_POST['number_id'];
			$secret = $_POST['secret'];
			$ga = new PHPGangsta_GoogleAuthenticator();
			$qrCodeUrl = $ga->getQRCodeGoogleUrl("uid_".$pk_id, $secret);
			$oneCode = $ga->getCode($secret);
			$get_oneCode = $number_id;
			if($oneCode == $get_oneCode){
				$sql_update = " UPDATE `v_parent_node` SET `otp_sercet` = '".$secret."' WHERE `pk_id` = ".$pk_id." ";
				mysqli_query($conn, $sql_update);
				$arr = array(
					"code" => 1,
					"message" => "SUCC"
				);
			}else{
				$arr = array(
					"code" => 0,
					"message" => "NO_OTP"
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
	}else if($result == 'u_authenticate_termination'){
		if($conn){
			$m_user_id = $_POST['m_user_id'];
			$m_password = mysql_new_password($_POST['m_password']);
			$m_otp_number = $_POST['m_otp_number'];

			$sql_select = " SELECT `pk_id`,`otp_sercet` FROM `v_parent_node` WHERE `user_id` = '".$m_user_id."' AND `user_password` = '".$m_password."' ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				require_once './class/GoogleAuthenticator.php';
				$ga = new PHPGangsta_GoogleAuthenticator();
				$secret = $row['otp_sercet'];
				$qrCodeUrl = $ga->getQRCodeGoogleUrl("uid_".$row['pk_id'], $secret);
				$oneCode = $ga->getCode($secret);
				$get_oneCode = $m_otp_number;

				if($oneCode == $get_oneCode){
					$sql_update = " UPDATE `v_parent_node` SET `otp_sercet` = 0 WHERE `user_id` = '".$m_user_id."' AND `user_password` = '".$m_password."' ";
					mysqli_query($conn, $sql_update);
					$arr = array(
						"code" => 1,
						"message" => "SUCC"
					);
				}else{
					$arr = array(
						"code" => 0,
						"message" => "NO_OTP"
					);
				}
			}else{
				$arr = array(
					"code" => 0,
					"message" => "NO_PA"
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
	}else if($result == 'u_multi_account'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$d_m_user_id = $_POST['d_m_user_id'];
			$d_m_user_password = mysql_new_password($_POST['d_m_user_password']);
			$d_m_user_otp_sercet = $_POST['d_m_user_otp_sercet'];

			$sql_select = " SELECT `pk_id`,`otp_sercet` FROM `v_parent_node` WHERE `user_id` = '".$d_m_user_id."' AND `user_password` = '".$d_m_user_password."' ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				if($row['otp_sercet'] == "0"){
					$arr = array(
						"code" => 0,
						"message" => "OTP"
					);
				}else{
					require_once './class/GoogleAuthenticator.php';
					$ga = new PHPGangsta_GoogleAuthenticator();
					$secret = $row['otp_sercet'];
					$qrCodeUrl = $ga->getQRCodeGoogleUrl("uid_".$row['pk_id'], $secret);
					$oneCode = $ga->getCode($secret);
					$get_oneCode = $d_m_user_otp_sercet;
					$aaa = $row['otp_sercet'];

					if($oneCode == $get_oneCode){
						$sql_update = " UPDATE `v_parent_node` SET `otp_sercet` = '".$aaa."' WHERE `pk_id` = ".$pk_id." ";
						mysqli_query($conn, $sql_update);

						$arr = array(
							"code" => 1,
							"message" => "SUCC"
						);
					}else{
						$arr = array(
							"code" => 0,
							"message" => "NO_OTP"
						);
					}
				}
			}else{
				$arr = array(
					"code" => 0,
					"message" => "ID"
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
	}else if($result == 'u_queren_button'){
		if($conn){
			$p_uid = $_POST['p_uid'];
			$pk_id = $_POST['pk_id'];

			$row_p = post($http_url, array('result'=>'u_queren_button', 'data'=>$p_uid));
			$row_p = json_decode($row_p, JSON_UNESCAPED_UNICODE);

			if($row_p["code"] == 1){
				$arr = array(
					"code" => 1,
					"id" => $row_p['id'],
					"user_name" => $row_p['user_name'],
					"idcard" => ($row_p['idcard']==null?0:$row_p['idcard']),
					"message" => "SUCC"
				);
			}else{
				if($row_p['message'] == "FAIL"){
					$arr = array(
						"code" => 0,
						"message" => "FAIL"
					);
				}else if($row_p['message'] == "NO"){
					$arr = array(
						"code" => 0,
						"message" => "NO"
					);
				}
			}
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_queren_uid'){
		if($conn){
			require_once './class/GoogleAuthenticator.php';
			$paybank_uid = $_POST['paybank_uid'];
			$google_otp = $_POST['google_otp'];
			$pk_id = $_POST['pk_id'];
			$idcard_id = $_POST['idcard_id'];

			$sql_select = " SELECT `otp_sercet` FROM `v_parent_node` WHERE `pk_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);
			$ga = new PHPGangsta_GoogleAuthenticator();
			$secret = $row['otp_sercet'];
			$qrCodeUrl = $ga->getQRCodeGoogleUrl("uid_".$agent_id, $secret);
			$oneCode = $ga->getCode($secret);
			$get_oneCode = $google_otp;

			if($oneCode == $get_oneCode){
				$sql_update = " UPDATE `v_parent_node` SET `p_uid` = ".$paybank_uid.", `idcard_id` = '".$idcard_id."' WHERE `pk_id` = ".$pk_id." ";
				mysqli_query($conn, $sql_update);
				$arr = array(
					"code" => 1,
					"message" => "SUCC"
				);
			}else{
				$arr = array(
					"code" => 0,
					"message" => "OTP"
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
		if($conn){
			$pk_id = $_POST['pk_id'];
			$w_price = $_POST['w_price'];

			$sql_select = " SELECT `p_uid` FROM `v_parent_node` WHERE `pk_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['p_uid'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NO"
				);
			}else{
				$sql_s_w = " SELECT `pk_id` FROM `v_withdraw_apply` WHERE `agent_id` = ".$pk_id." AND `status` = 0 ";
				$res_s_w = mysqli_query($conn, $sql_s_w);
				$row_s_w = mysqli_fetch_array($res_s_w);

				if($row_s_w['pk_id']){
					$arr = array(
						"code" => 0,
						"message" => "YOU"
					);
				}else{
					// 144, 145, 146, 147, 148, 149, 215  (출금시 구매 안해도 출금 가능한 user_id)
					$row_p = post($http_url, array('result'=>'u_withdraw_action', 'data'=>$row['p_uid']));
					$row_p = json_decode($row_p, JSON_UNESCAPED_UNICODE);
					if($row_p["code"] == 1){
						$sql_s_c = " SELECT `parent_pax` FROM `v_coin_wallet` WHERE `agent_id` = ".$pk_id." ";
						$res_s_c = mysqli_query($conn, $sql_s_c);
						$row_s_c = mysqli_fetch_array($res_s_c);
						$time = time();

						if($row_s_c['parent_pax'] > 0 && $w_price <= $row_s_c['parent_pax']){
							$sql_insert = " INSERT INTO `v_withdraw_apply` (`pk_id`,`agent_id`,`paybank_id`,`paybank_name`,`parent_pax`,`status`,`memo_lan`,`insert_time`,`update_time`) VALUES ";
							$sql_insert .= " (NULL,".$pk_id.",".$row_p['id'].",'".$row_p['user_name']."',".$w_price.",0,'정확한 심사 부탁드립니다.','".$time."','".$time."') ";
							mysqli_query($conn, $sql_insert);

							$arr = array(
								"code" => 1,
								"message" => "SUCC"
							);
						}else{
							$arr = array(
								"code" => 0,
								"cccc" => $row_s_c['parent_pax'],
								"message" => "NB"
							);
						}
					}else{
						if($row_p['message'] == "FAIL"){
							$arr = array(
								"code" => 0,
								"message" => "FAIL"
							);
						}else if($row_p['message'] == "NO"){
							$arr = array(
								"code" => 0,
								"message" => "NNO"
							);
						}else{
							$arr = array(
								"code" => 0,
								"message" => "FAIL"   //             
							);
						}
					}
					// $sql_sf = " SELECT `update_time` FROM `v_login_payment` WHERE `agent_id` = ".$pk_id." AND `status` = 1 AND `t_status` = 2 ORDER BY `pk_id` DESC LIMIT 1 ";
					// $res_sf = mysqli_query($conn, $sql_sf);
					// $row_sf = mysqli_fetch_array($res_sf);

					// if($row_sf['update_time']){
					// 	$time = time();
					// 	$b_time = date("Y-m-d", strtotime("-90 day", $time));
					// 	$b_time = strtotime($b_time);

					// 	if($row_sf['update_time'] < $b_time){
					// 		$arr = array(
					// 			"code" => 0,
					// 			"message" => "NNN"
					// 		);
					// 	}else{
					// 		$row_p = post($http_url, array('result'=>'u_withdraw_action', 'data'=>$row['p_uid']));
					// 		$row_p = json_decode($row_p, JSON_UNESCAPED_UNICODE);

					// 		if($row_p["code"] == 1){
					// 			$sql_s_c = " SELECT `parent_pax` FROM `v_coin_wallet` WHERE `agent_id` = ".$pk_id." ";
					// 			$res_s_c = mysqli_query($conn, $sql_s_c);
					// 			$row_s_c = mysqli_fetch_array($res_s_c);
					// 			$time = time();

					// 			if($row_s_c['parent_pax'] > 0 && $w_price <= $row_s_c['parent_pax']){
					// 				$sql_insert = " INSERT INTO `v_withdraw_apply` (`pk_id`,`agent_id`,`paybank_id`,`paybank_name`,`parent_pax`,`status`,`memo_lan`,`insert_time`,`update_time`) VALUES ";
					// 				$sql_insert .= " (NULL,".$pk_id.",".$row_p['id'].",'".$row_p['user_name']."',".$w_price.",0,'정확한 심사 부탁드립니다.','".$time."','".$time."') ";
					// 				mysqli_query($conn, $sql_insert);

					// 				$arr = array(
					// 					"code" => 1,
					// 					"message" => "SUCC"
					// 				);
					// 			}else{
					// 				$arr = array(
					// 					"code" => 0,
					// 					"cccc" => $row_s_c['parent_pax'],
					// 					"message" => "NB"
					// 				);
					// 			}
					// 		}else{
					// 			if($row_p['message'] == "FAIL"){
					// 				$arr = array(
					// 					"code" => 0,
					// 					"message" => "FAIL"
					// 				);
					// 			}else if($row_p['message'] == "NO"){
					// 				$arr = array(
					// 					"code" => 0,
					// 					"message" => "NNO"
					// 				);
					// 			}else{
					// 				$arr = array(
					// 					"code" => 0,
					// 					"message" => "FAIL"
					// 				);
					// 			}
					// 		}
					// 	}
					// }else{
					// 	$arr = array(
					// 		"code" => 0,
					// 		"message" => "NNN"
					// 	);
					// }
				}
			}
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_bank_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_status = $_POST['s_status'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_withdraw_apply` WHERE `agent_id` = ".$pk_id." ";
			if($s_status != 9 && $s_status != ""){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_withdraw_apply` WHERE `agent_id` = ".$pk_id." ";
			if($s_status != 9 && $s_status != ""){ $sql_sum .= " AND `status` = ".$s_status." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `update_time` < '".$s_stop_date."' "; }
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'u_bank'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_status = $_POST['s_status'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_withdraw_apply` WHERE `agent_id` = ".$pk_id." ";
			if($s_status != 9 && $s_status != ""){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"paybank_id" => $row['paybank_id'],
					"paybank_name" => $row['paybank_name'],
					"parent_pax" => $row['parent_pax'],
					"status" => $row['status'],
					"memo_lan" => $row['memo_lan'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'as_token'){
		if($conn){
			$sql_select = " SELECT `linked_account` FROM `v_token_wallet` WHERE `pk_id` = 1 ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$arr = array(
				"code" => 1,
				"message" => $row['linked_account']
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_bank_count'){
		if($conn){
			$s_status = $_POST['s_status'];
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$sc_user_id = $_POST['sc_user_id'];
			$sc_user_name = $_POST['sc_user_name'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$parent_id = 0;

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_withdraw_apply` WHERE `pk_id` != '' ";
			if($s_user_id != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' AND `user_id` = '".$s_user_id."' ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_status != 9 && $s_status != ""){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($sc_user_id != ""){ $sql_select .= " AND `paybank_id` = '".$sc_user_id."' "; }
			if($sc_user_name != ""){ $sql_select .= " AND `paybank_name` = '".$sc_user_name."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_withdraw_apply` WHERE `pk_id` != '' ";
			if($s_status != 9 && $s_status != ""){ $sql_sum .= " AND `status` = ".$s_status." "; }
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_sum .= " AND `agent_id` = ".$parent_id." "; }
			if($sc_user_id != ""){ $sql_sum .= " AND `paybank_id` = '".$sc_user_id."' "; }
			if($sc_user_name != ""){ $sql_sum .= " AND `paybank_name` = '".$sc_user_name."' "; }
			if($s_start_date != ""){ $sql_sum .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `update_time` < '".$s_stop_date."' "; }
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_bank'){
		if($conn){
			$s_status = $_POST['s_status'];
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$sc_user_id = $_POST['sc_user_id'];
			$sc_user_name = $_POST['sc_user_name'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];
			$parent_id = 0;

			$sql_select = " SELECT * FROM `v_withdraw_apply` WHERE `pk_id` != '' ";
			if($s_user_id != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' AND `user_id` = '".$s_user_id."' ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_status != 9 && $s_status != ""){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($sc_user_id != ""){ $sql_select .= " AND `paybank_id` = '".$sc_user_id."' "; }
			if($sc_user_name != ""){ $sql_select .= " AND `paybank_name` = '".$sc_user_name."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);

			while($row = mysqli_fetch_array($result)){
				$sql_sssss = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_sssss = mysqli_query($conn, $sql_sssss);
				$row_sssss = mysqli_fetch_array($res_sssss);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"paybank_id" => $row['paybank_id'],
					"paybank_name" => $row['paybank_name'],
					"user_name" => $row_sssss['user_name'],
					"user_id" => $row_sssss['user_id'],
					"parent_pax" => $row['parent_pax'],
					"status" => $row['status'],
					"memo_lan" => $row['memo_lan'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_withdraw_reject'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$reason_rejection = $_POST['reason_rejection'];
			$time = time();

			$sql_update = " UPDATE `v_withdraw_apply` SET `status` = 2, `memo_lan` = '".$reason_rejection."', `update_time` = '".$time."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_paybank_bank_withdraw'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_select = " SELECT `pk_id`,`agent_id`,`paybank_id`,`paybank_name`,`parent_pax` FROM `v_withdraw_apply` WHERE `pk_id` = ".$j_pk_id." AND `status` = 0 ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_ss = " SELECT `token`,`random_key`,`linked_account` FROM `v_token_wallet` WHERE `pk_id` = 1 ";
			$res_ss = mysqli_query($conn, $sql_ss);
			$row_ss = mysqli_fetch_array($res_ss);

			$res = array(
				"pk_id" => $row['pk_id'],
				"agent_id" => $row['agent_id'],
				"paybank_id" => $row['paybank_id'],
				"paybank_name" => $row['paybank_name'],
				"parent_pax" => $row['parent_pax'],
				"token" => $row_ss['token'],
				"random_key" => $row_ss['random_key'],
				"linked_account" => $row_ss['linked_account']
			);
			$res = json_encode($res, JSON_UNESCAPED_UNICODE);
			$arr = array(
				"code" => 1,
				"message" => $res
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_add_marketing'){
		if($conn){
			$notice_title = $_POST['notice_title'];
			$notice_content = $_POST['notice_content'];
			$img_file = isset($_FILES['img_file']);              // IMG 파일 체크
			$zip_file = isset($_FILES['zip_file']);              // ZIP 파일 체크
			$img_file_name = $_FILES['img_file']['name'];        // IMG 파일 이름
			$zip_file_name = $_FILES['zip_file']['name'];        // ZIP 파일 이름

			if($img_file && $img_file_name != "" && $zip_file && $zip_file_name != ""){
				$zip_upload_d = "./data/zipmain/";
				$img_upload_m = "./data/imgmain/";
				$img_upload_t = "./data/imgthum/";
				$time = time();

				$ext_zip = "zip";
				$ext_img = "jpg,gif,png";
				$allowed_extensions = explode(',', $ext_zip);
				$max_file_size = 5242880;
				$ext = substr($zip_file_name, strrpos($zip_file_name, '.') + 1);     // zip 확장명
				if(!in_array($ext, $allowed_extensions)) {
					echo "업로드할 수 없는 확장자 입니다.";
				}
				if($_FILES['zip_file']['size'] >= $max_file_size) {
					echo "5MB 까지만 업로드 가능합니다.";
				}
				$path = $time.'.'.$ext;                                // 업로드 될 파일 이름 zip
				if(move_uploaded_file($_FILES['zip_file']['tmp_name'], $zip_upload_d.$path)){
					$img_m_all = explode(',', $ext_img);
					$ext_i = substr($img_file_name, strrpos($img_file_name, '.') + 1);   // png 확장명

					if(!in_array($ext_i, $img_m_all)) { echo "업로드할 수 없는 확장자 입니다."; }
					if($_FILES['img_file']['size'] >= $max_file_size) { echo "5MB 까지만 업로드 가능합니다."; }

					$path_img = $time.'.'.$ext_i;                    // 얼로드 될 파일 이름 img
					if(move_uploaded_file($_FILES['img_file']['tmp_name'], $img_upload_m.$path_img)) {
						$info_image = getimagesize($img_upload_m.$path_img);
						switch($info_image['mime']){
							case "image/gif";
								$new_image=imagecreatefromgif($img_upload_m.$path_img);
								break;
							case "image/jpg";
								$new_image=imagecreatefromjpeg($img_upload_m.$path_img);
								break;
							case "image/png";
								$new_image=imagecreatefrompng($img_upload_m.$path_img);
								break;
							case "image/jpeg";
								$new_image=imagecreatefromjpeg($img_upload_m.$path_img);   // imgmain,   imgthum,   zipmain
								break;
						}

						if($new_image){
							$w=50;
							$h=50;
							$canvas=imagecreatetruecolor($w,$h);
							imagecopyresampled($canvas,$new_image,0,0,0,0,$w,$h,$info_image[0],$info_image[1]);
							$dir_n=$img_upload_t.$path_img;
							imagegif($canvas,$dir_n);

							$sql_insert = " INSERT INTO `v_marketing` (`pk_id`,`title`,`content`,`insert_time`,`status`,`img_path`,`zip_path`) VALUES ";
							$sql_insert .= " (NULL,'".$notice_title."','".$notice_content."','".$time."','A','".$path_img."','".$path."') ";
							mysqli_query($conn, $sql_insert);
							print "<script language=javascript> alert('저장성공'); location.replace('http://agent.livilcorp.com/version/business/admin/a_marketing.html');</script>";
						}else{ print "<script language=javascript> alert('저장실패'); history.back(-1); </script>"; }
					}else{ print "<script language=javascript> alert('업로드 실패2'); history.back(-1); </script>"; }
				}else{ print "<script language=javascript> alert('업로드 실패1'); history.back(-1); </script>"; }
			}else{ print "<script language=javascript> alert('파일이 업로드 되지 않았습니다.'); history.back(-1); </script>"; }
		}else{ print "<script language=javascript> alert('서버연결 실패.'); history.back(-1); </script>"; }
	}else if($result == 'a_marketing_delete'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_select = " SELECT `img_path`,`zip_path` FROM `v_marketing` WHERE `pk_id` = ".$j_pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);
			$zip_upload_d = "./data/zipmain/".$row['zip_path'];
			$img_upload_m = "./data/imgmain/".$row['img_path'];
			$img_upload_t = "./data/imgthum/".$row['img_path'];

			unlink($zip_upload_d);
			unlink($img_upload_m);
			unlink($img_upload_t);

			$sql_delete = " DELETE FROM `v_marketing` WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_delete);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_marketing_modifined'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_select = " SELECT * FROM `v_marketing` WHERE `pk_id` = ".$j_pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$res = array(
				"pk_id" => $row['pk_id'],
				"title" => $row['title'],
				"content" => $row['content'],
				"insert_time" => $row['insert_time'],
				"status" => $row['status']
			);
			$res = json_encode($res, JSON_UNESCAPED_UNICODE);
			$arr = array(
				"code" => 1,
				"message" => $res
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_marketing_modifined_action'){
		if($conn){
			$marketing_title = $_POST['marketing_title'];
			$marketing_content = $_POST['marketing_content'];
			$j_pk_id = $_POST['j_pk_id'];

			$sql_update = " UPDATE `v_marketing` SET `title` = '".$marketing_title."', `content` = '".$marketing_content."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_marketing'){
		if($conn){
			$sql_select = " SELECT * FROM `v_marketing` WHERE `status` = 'A' ORDER BY `pk_id` DESC ";
			$result = mysqli_query($conn, $sql_select);
			strip_tags();
			
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"title" => $row['title'],
					"content" => $row['content'],
					"insert_time" => $row['insert_time'],
					"status" => $row['status'],
					"img_path" => $row['img_path'],
					"zip_path" => $row['zip_path']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_qa_list'){
		if($conn){
			$sql_select = " SELECT * FROM `v_question_answer` ORDER BY `pk_id` DESC ";
			$result = mysqli_query($conn, $sql_select);
			strip_tags();
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"title" => $row['title'],
					"content" => $row['content'],
					"content_2" => strip_tags($row['content']),
					"status" => strip_tags($row['status']),
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_qa_add'){
		if($conn){
			$qa_select = $_POST['qa_select'];
			$qa_title = $_POST['qa_title'];
			$qa_content = $_POST['qa_content'];
			$time = time();

			$sql_insert = " INSERT INTO `v_question_answer` (`pk_id`,`title`,`content`,`status`,`insert_time`) VALUES (NULL,'".$qa_title."','".$qa_content."',".$qa_select.",'".$time."') ";
			mysqli_query($conn, $sql_insert);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_qa_delete'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_delete = " DELETE FROM `v_question_answer` WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_delete);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_qa_modifined'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_select = " SELECT * FROM `v_question_answer` WHERE `pk_id` = ".$j_pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$res = array(
				"pk_id" => $row['pk_id'],
				"title" => $row['title'],
				"content" => $row['content'],
				"status" => $row['status'],
				"insert_time" => $row['insert_time']
			);
			$res = json_encode($res, JSON_UNESCAPED_UNICODE);
			$arr = array(
				"code" => 1,
				"message" => $res
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_modifined_content'){
		if($conn){
			$qa_title = $_POST['qa_title'];
			$qa_content = $_POST['qa_content'];
			$j_pk_id = $_POST['j_pk_id'];

			$sql_update = " UPDATE `v_question_answer` SET `title` = '".$qa_title."',`content` = '".$qa_content."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_qa_content'){
		if($conn){
			$option = $_POST['option'];
			$sql_select = " SELECT * FROM `v_question_answer` WHERE `pk_id` != '' ";
			if($option != 0 && $option != ""){ $sql_select .= " AND `status` = ".$option." "; }
			$sql_select .= " ORDER BY `pk_id` DESC ";
			$result = mysqli_query($conn, $sql_select);
			strip_tags();
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"title" => $row['title'],
					"content" => $row['content'],
					"content_2" => strip_tags($row['content']),
					"status" => $row['status'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_star_bonus_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_company_bonus` WHERE `agent_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_company_bonus` WHERE `agent_id` = ".$pk_id." ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);
			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => $row_sum['parent_pax'],
					"message" => $row['count']
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
	}else if($result == 'u_star_bonus'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_company_bonus` WHERE `agent_id` = ".$pk_id." ";
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_star_bonus_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$parent_id = 0;

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_company_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != "" || $s_user_name != "" || $s_user_phon != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				if($s_user_name != ""){ $sql_s .= " AND `user_name` = '".$s_user_name."' "; }
				if($s_user_phon != ""){ $sql_s .= " AND `user_mobile` = '".$s_user_phon."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT ";
			$sql_sum .= " (SELECT SUM(`parent_pax`) FROM `v_company_bonus` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_sum .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_sum .= " ) AS `parent_pax` ";
			$sql_sum .= " FROM `v_company_bonus` LIMIT 1 ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_star_bonus'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_name = $_POST['s_user_name'];
			$s_user_id = $_POST['s_user_id'];
			$s_user_phon = $_POST['s_user_phon'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];
			$parent_id = 0;

			$sql_select = " SELECT * FROM `v_company_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != "" || $s_user_name != "" || $s_user_phon != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				if($s_user_name != ""){ $sql_s .= " AND `user_name` = '".$s_user_name."' "; }
				if($s_user_phon != ""){ $sql_s .= " AND `user_mobile` = '".$s_user_phon."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_s['user_name'],
					"user_id" => $row_s['user_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_vat_bonus_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$parent_id = 0;

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_vat_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT ";
			$sql_sum .= " (SELECT SUM(`parent_pax`) FROM `v_vat_bonus` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_sum .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_sum .= " ) AS `parent_pax` ";
			$sql_sum .= " FROM `v_vat_bonus` LIMIT 1 ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_vat_bonus'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];
			$parent_id = 0;

			$sql_select = " SELECT * FROM `v_vat_bonus` WHERE `pk_id` != '' ";
			if($s_user_id != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_s['user_name'],
					"user_id" => $row_s['user_id'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_withdraw_update'){
		if($conn){
			$j_token = $_POST['j_token'];
			$j_randomKey = $_POST['j_randomKey'];
			$linked_account = $_POST['linked_account'];

			$sql_update = " UPDATE `v_token_wallet` SET `token` = '".$j_token."', `random_key` = '".$j_randomKey."', `linked_account` = '".$linked_account."' WHERE `pk_id` = 1 ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_update_withdraw'){
		if($conn){ 
			$shouxufei = $_POST['shouxufei'];         // 출금 수수료 뺀금액
			$shouxu_3 = $_POST['shouxu_3'];           // 3.3 뺀금액
			$parent_pax = $_POST['parent_pax'];       // 실제 출금 금액
			$j_pk_id = $_POST['j_pk_id'];             // pk_id
			$j_agent_id = $_POST['j_agent_id'];       // agent_id
			$j_paybank_id = $_POST['j_paybank_id'];   // paybank_id
			$j_paybank_name = $_POST['j_paybank_name'];// paybank_name
			$j_parent_pax = $_POST['j_parent_pax'];
			$time = time();

			$sql_update = " UPDATE `v_withdraw_apply` SET `status` = 1, `memo_lan` = '출금 되었습니다.', `update_time` = '".$time."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);

			$sql_u_2 = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` - ".$j_parent_pax.", `withdraw_pax` = `withdraw_pax` + ".$j_parent_pax.", `update_time` = '".$time."' WHERE `agent_id` = ".$j_agent_id." ";
			mysqli_query($conn, $sql_u_2);

			$sql_u_3 = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + 1.5 WHERE `pk_id` = 144 ";
			mysqli_query($conn, $sql_u_3);

			$sql_u_4 = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + 0.5 WHERE `pk_id` = 215 ";
			mysqli_query($conn, $sql_u_4);

			$sql_i_1 = " INSERT INTO `v_fees` (`pk_id`,`agent_id`,`paybank_id`,`paybank_name`,`parent_pax`,`status`,`insert_time`) VALUES (NULL,".$j_agent_id.",".$j_paybank_id.",'".$j_paybank_name."',1.5,1,'".$time."') ";
			mysqli_query($conn, $sql_i_1);

			$sql_i_2 = " INSERT INTO `v_withdraw_vat` (`pk_id`,`agent_id`,`total_pax`,`parent_pax`,`percent`,`insert_time`) VALUES (NULL,".$j_agent_id.",".$shouxufei.",".$parent_pax.",'3.3','".$time."') ";
			mysqli_query($conn, $sql_i_2);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_business_tax_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_withdraw_vat` WHERE `agent_id` = ".$pk_id." ";
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_withdraw_vat` WHERE `agent_id` = ".$pk_id." ";
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'u_business_tax'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_withdraw_vat` WHERE `agent_id` = ".$pk_id." ";
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"total_pax" => $row['total_pax'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_business_tax_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$parent_id = 0;

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_withdraw_vat` WHERE `pk_id` != '' ";
			if($s_user_id != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT ";
			$sql_sum .= " (SELECT SUM(`parent_pax`) FROM `v_withdraw_vat` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_sum .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_sum .= " ) AS `parent_pax` ";
			$sql_sum .= " FROM `v_withdraw_vat` LIMIT 1 ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_business_tax'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];
			$parent_id = 0;

			$sql_select = " SELECT * FROM `v_withdraw_vat` WHERE `pk_id` != '' ";
			if($s_user_id != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_s['user_name'],
					"user_id" => $row_s['user_id'],
					"total_pax" => $row['total_pax'],
					"parent_pax" => $row['parent_pax'],
					"percent" => $row['percent'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_withdraw_fee_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$parent_id = 0;

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_fees` WHERE `pk_id` != '' ";
			if($s_user_id != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT ";
			$sql_sum .= " (SELECT SUM(`parent_pax`) FROM `v_fees` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_sum .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_sum .= " ) AS `parent_pax` ";
			$sql_sum .= " FROM `v_fees` LIMIT 1 ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_withdraw_fee'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_user_id = $_POST['s_user_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];
			$parent_id = 0;

			$sql_select = " SELECT * FROM `v_fees` WHERE `pk_id` != '' ";
			if($s_user_id != ""){
				$sql_s = " SELECT `pk_id` FROM `v_parent_node` WHERE `pk_id` != '' ";
				if($s_user_id != ""){ $sql_s .= " AND `user_id` = '".$s_user_id."' "; }
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);
				if($row_s['pk_id']){ $parent_id = $row_s['pk_id']; }
			}
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($parent_id != 0){ $sql_select .= " AND `agent_id` = ".$parent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_s['user_name'],
					"user_id" => $row_s['user_id'],
					"paybank_id" => $row['paybank_id'],
					"paybank_name" => $row['paybank_name'],
					"parent_pax" => $row['parent_pax'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_branch_setting_action'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$m_branch_id = $_POST['m_branch_id'];
			$m_memolan = $_POST['m_memolan'];

			$sql_update = " UPDATE `v_parent_node` SET `branch_settings` = '".$m_branch_id."', `memolan` = '".$m_memolan."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_card_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_card_num = $_POST['s_card_num'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_card_list` WHERE `agent_id` = ".$pk_id." ";
			if($s_card_num != ""){ $sql_select .= " AND `card_num` = '".$s_card_num."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $row['count'] 
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
	}else if($result == 'u_card'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_card_num = $_POST['s_card_num'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_card_list` WHERE `agent_id` = ".$pk_id." ";
			if($s_card_num != ""){ $sql_select .= " AND `card_num` = '".$s_card_num."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"card_num" => $row['card_num'],
					"total_fee" => $row['total_fee'],
					"insert_time" => $row['insert_time'],
					"memo_lan" => $row['memo_lan']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_add_card'){
		if($conn){
			$i_card_num = $_POST['i_card_num'];
			$i_memo_lan = $_POST['i_memo_lan'];
			$pk_id = $_POST['pk_id'];
			$time = time();

			$sql_select = " SELECT `pk_id` FROM `v_card_list` WHERE `card_num` = '".$i_card_num."' ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				$arr = array(
					"code" => 0,
					"message" => "YOU"
				);
			}else{
				$sql_insert = " INSERT INTO `v_card_list` (`pk_id`,`agent_id`,`card_num`,`insert_time`,`total_fee`,`memo_lan`) VALUES (NULL,".$pk_id.",'".$i_card_num."','".$time."',0,'".$i_memo_lan."') ";
				mysqli_query($conn, $sql_insert);
				$arr = array(
					"code" => 1,
					"message" => "SUCC"
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
	}else if($result == 'u_card_delete'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_delete = " DELETE FROM `v_card_list` WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_delete);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_modify_card'){
		if($conn){
			$m_card_num = $_POST['m_card_num'];
			$m_memo_lan = $_POST['m_memo_lan'];
			$ckckk = $_POST['ckckk'];
			$j_pk_id = $_POST['j_pk_id'];

			if($ckckk == 2){
				$sql_update = " UPDATE `v_card_list` SET `memo_lan` = '".$m_memo_lan."' WHERE `pk_id` = ".$j_pk_id." ";
				mysqli_query($conn, $sql_update);

				$arr = array(
					"code" => 1,
					"message" => "SUCC"
				);
			}else{
				$sql_select = " SELECT `pk_id` FROM `v_card_list` WHERE `card_num` = '".$m_card_num."' ";
				$result = mysqli_query($conn, $sql_select);
				$row = mysqli_fetch_array($result);

				if($row['pk_id']){
					$arr = array(
						"code" => 0,
						"message" => "YOU"
					);
				}else{
					$sql_update = " UPDATE `v_card_list` SET `card_num` = '".$m_card_num."', `memo_lan` = '".$m_memo_lan."' WHERE `pk_id` = ".$j_pk_id." ";
					mysqli_query($conn, $sql_update);

					$arr = array(
						"code" => 1,
						"message" => "SUCC"
					);
				}
			}
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_card_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_card_num = $_POST['s_card_num'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_card_list` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_card_num != ""){ $sql_select .= " AND `card_num` = '".$s_card_num."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $row['count']
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
	}else if($result == 'a_card'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_card_num = $_POST['s_card_num'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_card_list` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_card_num != ""){ $sql_select .= " AND `card_num` = '".$s_card_num."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_res = " SELECT `user_name`,`user_id`,`user_mobile` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_res = mysqli_query($conn, $sql_res);
				$row_res = mysqli_fetch_array($res_res);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_res['user_name'],
					"user_id" => $row_res['user_id'],
					"user_mobile" => $row_res['user_mobile'],
					"card_num" => $row['card_num'],
					"total_fee" => $row['total_fee'],
					"insert_time" => $row['insert_time'],
					"memo_lan" => $row['memo_lan']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_store_count'){
		if($conn_2){
			$pk_id = $_POST['pk_id'];
			$s_status = $_POST['s_status'];
			$s_store_id = $_POST['s_store_id'];
			$s_store_name = $_POST['s_store_name'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			
			$sql_select = " SELECT COUNT(*) AS `count` FROM `store_history` WHERE `agent_id` = ".$pk_id." AND `t_status` = 1 ";
			if($s_status != "" && $s_status != 0){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_store_id != ""){ $sql_select .= " AND `store_id` = ".$s_store_id." "; }
			if($s_store_name != ""){ $sql_select .= " AND `store_name` = '".$s_store_name."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn_2, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`total_fee`) AS `total_fee` FROM `store_history` WHERE `agent_id` = ".$pk_id." AND `t_status` = 1 ";
			if($s_status != "" && $s_status != 0){ $sql_sum .= " AND `status` = ".$s_status." "; }
			if($s_store_id != ""){ $sql_sum .= " AND `store_id` = ".$s_store_id." "; }
			if($s_store_name != ""){ $sql_sum .= " AND `store_name` = '".$s_store_name."' "; }
			if($s_start_date != ""){ $sql_sum .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `update_time` < '".$s_stop_date."' "; }
			$res_sum = mysqli_query($conn_2, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"total_fee" => ($row_sum['total_fee']==null?0:$row_sum['total_fee']),
					"message" => $row['count'] 
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
	}else if($result == 'u_store'){
		if($conn_2){
			$pk_id = $_POST['pk_id'];
			$s_status = $_POST['s_status'];
			$s_store_id = $_POST['s_store_id'];
			$s_store_name = $_POST['s_store_name'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `store_history` WHERE `agent_id` = ".$pk_id." AND `t_status` = 1 ";
			if($s_status != "" && $s_status != 0){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_store_id != ""){ $sql_select .= " AND `store_id` = ".$s_store_id." "; }
			if($s_store_name != ""){ $sql_select .= " AND `store_name` = '".$s_store_name."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn_2, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"store_id" => $row['store_id'],
					"store_name" => $row['store_name'],
					"store_account" => $row['store_account'],
					"status" => $row['status'],
					"total_fee" => $row['total_fee'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"sql_select" => $sql_select,
					"message" => $res_list
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
	}else if($result == 'u_add_store'){
		if($conn_2){
			$i_paybank_uid = $_POST['i_paybank_uid'];
			$pk_id = $_POST['pk_id'];
			$time = time();

			$sql_select = " SELECT `pk_id` FROM `store_history` WHERE `store_id` = ".$i_paybank_uid." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				$arr = array(
					"code" => 0,
					"message" => "YOU"
				);
			}else{
				$row_p = post($http_url, array('result'=>'add_store', 'data'=>$i_paybank_uid));
				$row_p = json_decode($row_p, JSON_UNESCAPED_UNICODE);

				if($row_p['id']){
					$sql_insert = " INSERT INTO `store_history` (`pk_id`,`agent_id`,`store_id`,`store_name`,`store_account`,`status`,`t_status`,`total_fee`,`insert_time`,`update_time`) VALUES ";
					$sql_insert .= " (NULL,".$pk_id.",".$row_p['id'].",'".$row_p['name']."','".$row_p['contact_mobile']."',3,1,0,'".$time."','".$time."') ";
					mysqli_query($conn_2, $sql_insert);

					$arr = array(
						"code" => 1,
						"message" => "SUCC"
					);
				}else{
					$arr = array(
						"code" => 0,
						"message" => "NO"
					);
				}
			}
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_store_count'){
		if($conn_2){
			$s_status = $_POST['s_status'];
			$s_agent_id = $_POST['s_agent_id'];
			$s_store_id = $_POST['s_store_id'];
			$s_store_name = $_POST['s_store_name'];
			$s_store_account = $_POST['s_store_account'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `store_history` WHERE `t_status` = 1 ";
			if($s_status != "" && $s_status != 0){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_store_id != ""){ $sql_select .= " AND `store_id` = ".$s_store_id." "; }
			if($s_store_name != ""){ $sql_select .= " AND `store_name` = '".$s_store_name."' "; }
			if($s_store_account != ""){ $sql_select .= " AND `store_account` = '".$s_store_account."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn_2, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`total_fee`) AS `total_fee` FROM `store_history` WHERE `t_status` = 1 ";
			if($s_status != "" && $s_status != 0){ $sql_sum .= " AND `status` = ".$s_status." "; }
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_store_id != ""){ $sql_sum .= " AND `store_id` = ".$s_store_id." "; }
			if($s_store_name != ""){ $sql_sum .= " AND `store_name` = '".$s_store_name."' "; }
			if($s_store_account != ""){ $sql_sum .= " AND `store_account` = '".$s_store_account."' "; }
			if($s_start_date != ""){ $sql_sum .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `update_time` < '".$s_stop_date."' "; }
			$res_sum = mysqli_query($conn_2, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"total_fee" => ($row_sum['total_fee']==null?0:$row_sum['total_fee']),
					"message" => $row['count'] 
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
	}else if($result == 'a_store'){
		if($conn_2){
			$s_status = $_POST['s_status'];
			$s_agent_id = $_POST['s_agent_id'];
			$s_store_id = $_POST['s_store_id'];
			$s_store_name = $_POST['s_store_name'];
			$s_store_account = $_POST['s_store_account'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `store_history` WHERE `t_status` = 1 ";
			if($s_status != "" && $s_status != 0){ $sql_select .= " AND `status` = ".$s_status." "; }
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_store_id != ""){ $sql_select .= " AND `store_id` = ".$s_store_id." "; }
			if($s_store_name != ""){ $sql_select .= " AND `store_name` = '".$s_store_name."' "; }
			if($s_store_account != ""){ $sql_select .= " AND `store_account` = '".$s_store_account."' "; }
			if($s_start_date != ""){ $sql_select .= " AND `update_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `update_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn_2, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_res = " SELECT `user_name`,`user_id`,`user_mobile` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_res = mysqli_query($conn, $sql_res);
				$row_res = mysqli_fetch_array($res_res);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_res['user_name'],
					"user_id" => $row_res['user_id'],
					"user_mobile" => $row_res['user_mobile'],
					"store_id" => $row['store_id'],
					"store_name" => $row['store_name'],
					"store_account" => $row['store_account'],
					"status" => $row['status'],
					"total_fee" => $row['total_fee'],
					"insert_time" => $row['insert_time'],
					"update_time" => $row['update_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'u_store_reexam'){
		if($conn_2){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_update = " UPDATE `store_history` SET `status` = 3 WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn_2, $sql_update);

			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'u_select_addres'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT `receiving_number`,`receiving_address` FROM `v_login_payment` WHERE `agent_id` = ".$pk_id." AND `receiving_address` != '' ORDER BY `pk_id` DESC LIMIT 1 ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['receiving_number']){
				$arr = array(
					"code" => 1,
					"receiving_number" => $row['receiving_number'],
					"receiving_address" => $row['receiving_address']
				);
			}else{
				$arr = array(
					"code" => 0,
					"message" => "NoData"
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
	}else if($result == 'u_order_pay'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];

			$sql_select = " SELECT `pk_id`,`order_value` FROM `v_login_payment` WHERE `pk_id` = ".$j_pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			if($row['pk_id']){
				$arr = array(
					"code" => 1,
					"message" => $row['order_value']
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
	}else if($result == 'u_repurchase_count'){
		if($conn){
			$pk_id = $_POST['pk_id'];

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_repurchase_bonus` WHERE `agent_id` = ".$pk_id." ";
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_repurchase_bonus` WHERE `agent_id` = ".$pk_id." ";
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);
			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => $row_sum['parent_pax'],
					"message" => $row['count']
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
	}else if($result == 'u_repurchase'){
		if($conn){
			$pk_id = $_POST['pk_id'];
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_repurchase_bonus` WHERE `agent_id` = ".$pk_id." ";
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"parent_pax" => $row['parent_pax'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_repurchase_count'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }

			$sql_select = " SELECT COUNT(*) AS `count` FROM `v_repurchase_bonus` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$result = mysqli_query($conn, $sql_select);
			$row = mysqli_fetch_array($result);

			$sql_sum = " SELECT SUM(`parent_pax`) AS `parent_pax` FROM `v_repurchase_bonus` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_sum .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_start_date != ""){ $sql_sum .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_sum .= " AND `insert_time` < '".$s_stop_date."' "; }
			$res_sum = mysqli_query($conn, $sql_sum);
			$row_sum = mysqli_fetch_array($res_sum);

			if($row['count'] == 0){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"parent_pax" => ($row_sum['parent_pax']==null?0:$row_sum['parent_pax']),
					"message" => $row['count'] 
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
	}else if($result == 'a_repurchase'){
		if($conn){
			$s_agent_id = $_POST['s_agent_id'];
			$s_start_date = strtotime($_POST['s_start_date']);
			$s_stop_date = $_POST['s_stop_date'];
			if($s_stop_date != ""){ $s_stop_date = strtotime($s_stop_date." 23:59:59"); }
			$s_data = $_POST['s_data'];
			$e_data = $_POST['e_data'];

			$sql_select = " SELECT * FROM `v_repurchase_bonus` WHERE `pk_id` != '' ";
			if($s_agent_id != ""){ $sql_select .= " AND `agent_id` = ".$s_agent_id." "; }
			if($s_start_date != ""){ $sql_select .= " AND `insert_time` > '".$s_start_date."' "; }
			if($s_stop_date != ""){ $sql_select .= " AND `insert_time` < '".$s_stop_date."' "; }
			$sql_select .= " ORDER BY `pk_id` DESC LIMIT ".$s_data.", ".$e_data." ";
			$result = mysqli_query($conn, $sql_select);
			while($row = mysqli_fetch_array($result)){
				$sql_s = " SELECT `user_name`,`user_id` FROM `v_parent_node` WHERE `pk_id` = ".$row['agent_id']." ";
				$res_s = mysqli_query($conn, $sql_s);
				$row_s = mysqli_fetch_array($res_s);

				$res_list[] = array(
					"pk_id" => $row['pk_id'],
					"agent_id" => $row['agent_id'],
					"user_name" => $row_s['user_name'],
					"user_id" => $row_s['user_id'],
					"parent_pax" => $row['parent_pax'],
					"insert_time" => $row['insert_time']
				);
			}
			$res_list = json_encode($res_list, JSON_UNESCAPED_UNICODE);
			if($res_list == "null"){
				$arr = array(
					"code" => 0,
					"message" => "NoData"
				);
			}else{
				$arr = array(
					"code" => 1,
					"message" => $res_list
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
	}else if($result == 'a_delivery_completed'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_update = " UPDATE `v_login_payment` SET `tt_status` = 1 WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_cancel_delivery'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$sql_update = " UPDATE `v_login_payment` SET `tt_status` = 2 WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_memo_lan'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$m_memo_lan = $_POST['m_memo_lan'];
			$sql_update = " UPDATE `v_login_payment` SET `memo_lan` = '".$m_memo_lan."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_phone_change'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$m_receiving_number = $_POST['m_receiving_number'];
			$sql_update = " UPDATE `v_login_payment` SET `receiving_number` = '".$m_receiving_number."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_address_change'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$m_receiving_address = $_POST['m_receiving_address'];
			$sql_update = " UPDATE `v_login_payment` SET `receiving_address` = '".$m_receiving_address."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'aa_memo_lan'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$m_memo_lan = $_POST['m_memo_lan'];
			$sql_update = " UPDATE `v_coin_wallet` SET `memo_lan` = '".$m_memo_lan."' WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}else if($result == 'a_price_modify'){
		if($conn){
			$j_pk_id = $_POST['j_pk_id'];
			$m_parent_pax = $_POST['m_parent_pax'];
			$sql_update = " UPDATE `v_coin_wallet` SET `parent_pax` = ".$m_parent_pax." WHERE `pk_id` = ".$j_pk_id." ";
			mysqli_query($conn, $sql_update);
			$arr = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}else{
			$arr = array(
				"code" => 0,
				"message" => "FAIL"
			);
		}
		$arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
		echo $arr;
	}

	function google_otp($pk_id){
		require_once './class/GoogleAuthenticator.php';

		$ga = new PHPGangsta_GoogleAuthenticator();
		$secret = $ga->createSecret(); // 시크릿키 생성
		$qrCodeUrl = $ga->getQRCodeGoogleUrl("UID_".$pk_id, $secret);
		$otc_code = array(
			"otc_code" =>  urlencode($qrCodeUrl),
			"secret" =>  $secret
		);
		$otc_code = json_encode($otc_code, JSON_UNESCAPED_UNICODE);
		return $otc_code;
	}

	function post($url, $fields){
		$post_field_string = http_build_query($fields, '', '&');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);
		curl_setopt($ch, CURLOPT_POST, true);
		$response = curl_exec($ch);
		curl_close ($ch);
		return $response;
	}

	function mysql_new_password($pw) { return strlen($pw)>0?strtoupper('*'.sha1(sha1($pw,true))):($pw=== null?null:''); }
	mysql_close($conn);
?>