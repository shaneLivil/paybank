document.writeln("   ");
document.writeln('	<nav class="navbar navbar-vertical fixed-left navbar-expand-md " id="sidebar">');
document.writeln('		<div class="container-fluid">');
document.writeln('			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">');
document.writeln('				<span class="navbar-toggler-icon"></span>');
document.writeln('			</button>');
document.writeln('		<a class="navbar-brand" href=javascript:left_menu("u_buy_package");><img src="../../assets/img/logo.png" class="navbar-brand-img mx-auto" alt="PayBankLogo"></a>');
document.writeln('		<div class="navbar-user d-md-none">');
document.writeln('		              <div class="dropdown">');
document.writeln('		                <a href="javascript:logout_left();" class="dropdown-toggle">');
document.writeln('		                 로그아웃');
document.writeln('		                </a>');
document.writeln('		              </div>');
document.writeln('		           </div>');
document.writeln('		<div class="collapse navbar-collapse" id="sidebarCollapse">');
document.writeln('			<ul class="navbar-nav">');
// document.writeln('				<li class="nav-item"><a class="nav-link" href=javascript:left_menu("dashboard");><i class="fe fe-activity"></i>Dashboard</a></li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-shopping-cart"></i>구매하기</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_buy_package");>상품 구매</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_order_history");>주문 내역</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-users"></i>나의팀 & 정보</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_mypage");>나의 정보</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_google_otp");>OTP 인증</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_parent_list");>추천인<a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_store_list");>가맹점<a></li>');
// document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_store_extra");>가맹점 수당<a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-archive"></i>지갑</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_wallet_list");>지갑 관리</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_bank_withdraw");>출금 관리</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_business_tax");>사업 소세</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-credit-card"></i>자산관리</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_join_bonus");>가입 수당</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_parent_bonus");>추천 수당</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_parent_matching_bonus");>추천 매칭</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_star_bonus");>스타 보너스</a></li>');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_repurchase_bonus");>재구매 보너스</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active"><i class="fe fe-credit-card"></i>카드</a>');
document.writeln('					<div class="collapse show">');
document.writeln('						<ul class="nav nav-sm flex-column">');
document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("u_card_list");>카드 관리</a></li>');
document.writeln('						</ul>');
document.writeln('					</div>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active" href=javascript:left_menu("u_about");><i class="fe fe-message-square"></i>회사소개</a>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active" href=javascript:left_menu("u_qa_content");><i class="fe fe-help-circle"></i>공지사항 & Q/A</a>');
document.writeln('				</li>');
document.writeln('				<li class="nav-item">');
document.writeln('					<a class="nav-link active" href=javascript:left_menu("u_marketing");><i class="fe fe-video"></i>마케팅 지원</a>');
document.writeln('				</li>');
// document.writeln('				<li class="nav-item">');
// document.writeln('					<a class="nav-link active" href=javascript:left_menu("marketing_plan");><i class="fe fe-volume-2"></i>마케팅 플랜</a>');
// document.writeln('				</li>');
// document.writeln('					</ul>');
// document.writeln('					<hr class="navbar-divider my-3">');
// document.writeln('                  <h6 class="navbar-heading">기타</h6>');
// document.writeln('					<ul class="navbar-nav mb-md-4">');
// document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("shopping_mall");><i class="fe fe-shopping-cart"></i>쇼핑몰</a></li>');
// document.writeln('							<li class="nav-item"><a class="nav-link" href=javascript:left_menu("paybank");><i class="fe fe-home"></i>페이뱅크</a></li>');
// document.writeln('					</ul>');
document.writeln('					<div class="mt-auto"></div>');
// document.writeln('					<button class="btn btn-block btn-primary mb-4" target="_blank" onclick=left_menu("app_download")><i class="fe fe-box mr-2"></i><span style="font-size: 0.9em;">페이뱅크 앱 다운로드</span></button>');
document.writeln('				</div>');
document.writeln('			</div>');
document.writeln('		</nav>');
document.writeln('		<nav id="sidebarSmall"></nav>');
document.writeln('		<div class="main-content" id="topnav">');
document.writeln('		</div>');


function left_menu(result){
	if(result == 'u_buy_package'){
		// 상품구매
		location.href="./u_buy_package.html";
	}else if(result == 'u_parent_list'){
		// 추천인 리스트
		location.href="./u_parent_list.html";
	}else if(result == 'u_support_list'){
		// 후원인 리스트
		location.href="./u_support_list.html";
	}else if(result == 'u_wallet_list'){
		// 지갑 관리
		location.href="./u_wallet_list.html";
	}else if(result == 'u_join_bonus'){
		// 회원가입 보너스
		location.href="./u_join_bonus.html";
	}else if(result == 'u_parent_bonus'){
		// 추천 수당
		location.href="./u_parent_bonus.html";
	}else if(result == 'u_parent_matching_bonus'){
		// 추천 매칭 보너스
		location.href="./u_parent_matching_bonus.html";
	}else if(result == 'u_order_history'){
		// 주문내역
		location.href="./u_order_history.html";
	}else if(result == 'u_mypage'){
		// 나의 정보
		location.href="./u_mypage.html";
	}else if(result == 'u_google_otp'){
		// OTP 인증
		location.href="./u_google_otp.html";
	}else if(result == 'u_bank_withdraw'){
		// 출금 관리
		location.href="./u_bank_withdraw.html";
	}else if(result == 'u_marketing'){
		// 마케팅 지원
		location.href="./u_marketing.html";
	}else if(result == 'u_qa_content'){
		// 질문답변
		location.href="./u_qa_content.html";
	}else if(result == 'u_star_bonus'){
		// 스타 보너스
		location.href="./u_star_bonus.html";
	}else if(result == 'u_business_tax'){
		// 사업 소세
		location.href="./u_business_tax.html";
	}else if(result == 'u_card_list'){
		// 카드 관리
		location.href="./u_card_list.html";
	}else if(result == 'u_store_list'){
		// 가맹점 관리
		location.href="./u_store_list.html";
	}else if(result == 'u_about'){
		// 회사소개
		location.href="./u_about.html";
	}else if(result == 'u_store_extra'){
		// 가맹점 수당
		location.href="./u_store_extra.html";
	}else if(result == 'u_repurchase_bonus'){
		// 재구매 보너스
		location.href="./u_repurchase_bonus.html";
	}
}

function logout_left(){
	var data = {
		result: 'u_logout'
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