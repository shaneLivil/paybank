<?php
	include './db/dbconfig.php';
	header('Content-Type: text/html; charset=UTF-8');
	date_default_timezone_set('Asia/Seoul');
	$result = $_POST['result'];

	if($result == "livil_card"){
		$merchant_id = $_POST['merchant_id'];
		$user_id = $_POST['user_id'];
		$qr_code_type = $_POST['qr_code_type'];
		$pay_fee = $_POST['pay_fee'];
		$receive_coin_id = $_POST['receive_coin_id'];
		$card_no = $_POST['card_no'];
		$remark = $_POST['remark'];
		$pay_order_num = $_POST['pay_order_num'];
		$time = time();

		if($qr_code_type == 3){
			if($receive_coin_id == 5){
				$fee_livil = round($pay_fee * 50 / 100, 8);     // 리빌
				$fee_card = round($pay_fee * 20 / 100, 8);      // 카드 등록인
				$fee_store = round($pay_fee * 10 / 100, 8);     // 가맹점 등록인
				$fee_cube = round($pay_fee * 10 / 100, 8);      // 큐브
				$fee_bran = round($pay_fee * 10 / 100, 8);      // 지사장
				/*
					1. merchant_id         -> 받은 가맹점 ID
					2. user_id             -> 결제인 UID
					3. qr_code_type        -> 1.QR코드 결제, 3.카드 결제
					4. amount              -> 결제 금액
					5. receive_coin_id     -> 실제 받은 코인 ID
					6. receive_coin_name   -> 실제 받은 코인 이름
					7. receive_coin_num    -> 실제 받은 코인 수량
					8. pay_fee             -> 수수료
					9. status              -> 3.결제 성공, 4.결제 실패
					10. card_no            -> 카드번호

					1. 큐브 수수료 금액에 10%                  +++++++
					2. 카드 등록인 수수료 금액에 20%             +++++++
					3. 리빌 수수료 금액에 50%                  +++++++
					4. 가맹점 신청 수수료 금액에 10%             +++++++
					5. 가맹점 지사장 수수료 금액에 10%            +++++++
					////////////////////////////////////////////////
					완성
					1. 카드 등록인 수수료 20% (완료)
					2. 큐브 수수료 10% (완료)
					3. 가맹점 등록인 수수료 10% (완료)
					4. 지사장 수수료 10% (완료)
				*/

				$sql_select = " SELECT `agent_id` FROM `v_card_list` WHERE `card_num` = '".$card_no."' ";
				$result = mysqli_query($conn, $sql_select);
				$row = mysqli_fetch_array($result);

				if($row['agent_id']){
					// 카드 등록인 수수료
					$sql_update = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$fee_card." WHERE `agent_id` = ".$row['agent_id']." ";
					mysqli_query($conn, $sql_update);

					// 큐브 수수료
					$sql_u_c = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$fee_cube." WHERE `agent_id` = 148 ";
					mysqli_query($conn, $sql_u_c);

					// 총 받은카드 수수료
					$sql_u_d = " UPDATE `v_card_list` SET `total_fee` = `total_fee` + ".$pay_fee." WHERE `card_num` = '".$card_no."' ";
					mysqli_query($conn, $sql_u_d);
					
					// insert 기록
					$sql_insert = " INSERT INTO `v_card_fees` (`pk_id`,`agent_id`,`card_num`,`pay_fee`,`livil_fee`,`card_fee`,`cube_fee`,`insert_time`,`pay_order_num`) VALUES ";
					$sql_insert .= " (NULL,".$row['agent_id'].",'".$card_no."',".$pay_fee.",".$fee_livil.",".$fee_card.",".$fee_cube.",'".$time."','".$pay_order_num."') ";
					mysqli_query($conn, $sql_insert);
				}

				$sql_s_st = " SELECT `agent_id`,`store_id`,`store_name`,`store_account`,`t_status` FROM `store_history` WHERE `store_id` = ".$merchant_id." AND `status` = 2 ";
				$res_s_st = mysqli_query($conn_2, $sql_s_st);
				$row_s_st = mysqli_fetch_array($res_s_st);

				if($row_s_st['agent_id']){
					// cube(큐브)
					if($row_s_st['t_status'] == 1){
						// 가맹점 등록인 수수료
						$sql_u_st = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$fee_store." WHERE `agent_id` = ".$row_s_st['agent_id']." ";
						mysqli_query($conn, $sql_u_st);

						// 총 가맹점 수수료 금액
						$sql_uuu = " UPDATE `store_history` SET `total_fee` = `total_fee` + ".$pay_fee." WHERE `store_id` = ".$merchant_id." ";
						mysqli_query($conn_2, $sql_uuu);

						// 지사장 수수료
						$sql_s_j = " SELECT B.`pk_id` FROM `v_parent_node` A, `v_parent_node` B WHERE A.`pk_id` = ".$row_s_st['agent_id']." AND A.`branch_settings` = B.`user_id` ";
						$res_s_j = mysqli_query($conn, $sql_s_j);
						$row_s_j = mysqli_fetch_array($res_s_j);
						$sj_pk_id = 0;

						if($row_s_j['pk_id']){
							if($row_s_j['pk_id'] == "" || $row_s_j['pk_id'] == null || $row_s_j['pk_id'] == "null"){
							}else{
								// 지사장 금액 플러스 해주기
								$sql_u_j = " UPDATE `v_coin_wallet` SET `parent_pax` = `parent_pax` + ".$fee_bran." WHERE `agent_id` = ".$row_s_j['pk_id']." ";
								mysqli_query($conn, $sql_u_j);
								$sj_pk_id = $row_s_j['pk_id'];
							}
						}

						// 가맹점 수수료 insert
						$sql_insert = " INSERT INTO `v_store_fees` (`pk_id`,`agent_id`,`parent_id`,`store_id`,`store_name`,`store_account`,`pay_fee`,`store_fee`,`bran_fee`,`insert_time`,`pay_order_num`) VALUES ";
						$sql_insert .= " (NULL,".$row_s_st['agent_id'].",".$sj_pk_id.",".$row_s_st['store_id'].",'".$row_s_st['store_name']."','".$row_s_st['store_account']."',".$pay_fee.",".$fee_store.",".$fee_bran.",'".$time."','".$pay_order_num."') ";
						mysqli_query($conn, $sql_insert);
					}
				}
			}
			$row_p = array(
				"code" => 1,
				"message" => "SUCC"
			);
		}

		$row_p = json_encode($row_p, JSON_UNESCAPED_UNICODE);
		echo $row_p;
	}
?>