document.writeln('	<nav class="navbar navbar-vertical fixed-left navbar-expand-md " id="sidebar">');
document.writeln('		<div class="container-fluid">');
document.writeln('			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">');
document.writeln('				<span class="navbar-toggler-icon"></span>');
document.writeln('			</button>');
document.writeln('		<a class="navbar-brand" href=javascript:left_menu("a_member_list");><img src="../../assets/img/logo.png" class="navbar-brand-img mx-auto" alt=""></a>');
document.writeln('		<div class="navbar-user d-md-none">');
document.writeln('		              <div class="dropdown">');
document.writeln('		                <a href="javascript:logout();" class="dropdown-toggle">');
document.writeln('		                 로그아웃');
document.writeln('		                </a>');
document.writeln('		              </div>');
document.writeln('		           </div>');
document.writeln('		<div class="collapse navbar-collapse" id="sidebarCollapse">');
document.writeln('			<ul class="navbar-nav">');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-users"></i>회원</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_member_list");>회원관리</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_store_list");>가맹점</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-credit-card"></i>자산관리</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_assets_list");>회원 자산</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_wallet_list");>지갑 관리</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_order_history");>주문 내역</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_bank_withdraw");>출금 관리</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_withdraw_fee");>출금 수수료</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_vat_bonus");>부가 가치세</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_business_tax");>사업 소세</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-credit-card"></i>수당관리</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_join_bonus");>가입 수당</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_parent_bonus");>추천 수당</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_parent_matching_bonus");>추천 매칭</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_star_bonus");>스타 보너스</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_repurchase_bonus");>재구매 보너스</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-credit-card"></i>카드</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_card_list");>카드 관리</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-users"></i>기타</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_qa_content");>공지사항 & Q/A</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("a_marketing");>마케팅 지원</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('					</ul>');
document.writeln('					<hr class="navbar-divider my-3">');
document.writeln('				</div>');
document.writeln('			</div>');
document.writeln('		</nav>');
document.writeln('		<nav id="sidebarSmall"></nav>');
document.writeln('		<div class="main-content" id="topnav">');
document.writeln('		</div>');

function left_menu(result){
    if(result == "a_member_list"){
        // 회원관리
        location.href="./a_member_list.html";
    }else if(result == 'a_assets_list'){
		// 자산관리
		location.href="./a_assets_list.html";
	}else if(result == 'a_wallet_list'){
		// 지갑관리
		location.href="./a_wallet_list.html";
	}else if(result == 'a_join_bonus'){
		// 회원가입 보너스
		location.href="./a_join_bonus.html";
	}else if(result == 'a_parent_bonus'){
		// 추천 수당
		location.href="./a_parent_bonus.html";
	}else if(result == 'a_parent_matching_bonus'){
		// 추천 매칭 보너스
		location.href="./a_parent_matching_bonus.html";
	}else if(result == 'a_marketing'){
		// 마케팅 지원
		location.href="./a_marketing.html";
	}else if(result == 'a_order_history'){
		// 주무내역
		location.href="./a_order_history.html";
	}else if(result == 'a_bank_withdraw'){
		// 출금관리
		location.href="./a_bank_withdraw.html";
	}else if(result == 'a_qa_content'){
		// 질문답변
		location.href="./a_qa_content.html";
	}else if(result == 'a_star_bonus'){
		// 스타 보너스
		location.href="./a_star_bonus.html";
	}else if(result == 'a_vat_bonus'){
		// 부가 가치세
		location.href="./a_vat_bonus.html";
	}else if(result == 'a_business_tax'){
		// 사업 소세
		location.href="./a_business_tax.html";
	}else if(result == 'a_withdraw_fee'){
		// 출금 수수료
		location.href="./a_withdraw_fee.html";
	}else if(result == 'a_card_list'){
		// 카드 관리
		location.href="./a_card_list.html";
	}else if(result == 'a_store_list'){
		// 가맹점
		location.href="./a_store_list.html";
	}else if(result == 'a_repurchase_bonus'){
		// 재구매 보너스
		location.href="./a_repurchase_bonus.html";
	}
}

function logout(){
	var data = {
		result: 'a_logout'
	}

	$.ajax({
		type: 'POST',
		url: '../send_db.php',
		dataType: 'text',
		data: data,
		success: function(data){
			var json_data = JSON.parse(data);
			if(json_data.code == 1){
				location.href="../../index.html";
			}
		},
		error: function(err){
			alert('error: ' + JSON.stringify(err));
		}
	});
}