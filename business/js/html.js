// 사용자 user(u_support_list.html)
function u_support_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        var name = data[i].user_name.substr(0, data[i].user_name.length - 1) + "*";
		var mobile = data[i].user_mobile.substr(0, 3) + "****" + data[i].user_mobile.substr(7, data[i].user_mobile.length);
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+mobile+'</td>';
        if(data[i].member_rock == 1){ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)미결제</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;"><button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=u_support_details('+data[i].pk_id+');>상세보기</button></td>';
        html += '</tr>';
    }
    $('#u_support_list').html(html);
}

// 사용자 user(u_support_details_append.html)
function u_support_details_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        var name = data[i].user_name.substr(0, data[i].user_name.length - 1) + "*";
        var mobile = data[i].user_mobile.substr(0, 3) + "****" + data[i].user_mobile.substr(7, data[i].user_mobile.length);
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+mobile+'</td>';
        if(data[i].member_rock == 1){ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)미결제</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;"><button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=u_support_details2('+data[i].pk_id+');>상세보기</button></td>';
        html += '</tr>';
    }
    $('#u_support_details').html(html);
}

// 사용자 user(u_parent_list.html)
function u_parent_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        var name = data[i].user_name.substr(0, data[i].user_name.length - 1) + "*";
        var mobile = data[i].user_mobile.substr(0, 3) + "****" + data[i].user_mobile.substr(7, data[i].user_mobile.length);
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+mobile+'</td>';
        if(data[i].member_rock == 1){ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)미결제</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;"><button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=u_parent_details('+data[i].pk_id+');>상세보기</button></td>';
        html += '</tr>';
    }
    $('#u_parent_list').html(html);
}

// 사용자 user(u_parent_details.html)
function u_parent_details_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        var name = data[i].user_name.substr(0, data[i].user_name.length - 1) + "*";
        var mobile = data[i].user_mobile.substr(0, 3) + "****" + data[i].user_mobile.substr(7, data[i].user_mobile.length);
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+mobile+'</td>';
        if(data[i].member_rock == 1){ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)미결제</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;"><button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=u_parent_details('+data[i].pk_id+');>상세보기</button></td>';
        html += '</tr>';
    }
    $('#u_parent_details').html(html);
}

// 사용자 user(u_wallet_list.html)
function u_wallet_append(data){
    var html = '';
    var date2 = new Date(data.update_time * 1000);
    var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
    var date3 = new Date(data.daily_time * 1000);
    var time3 = date3.getFullYear().toString() + "-" + (date3.getMonth() + 1).toString() + "-" + date3.getDate().toString() + " " + date3.getHours().toString() + ":" + date3.getMinutes().toString() + ":" + date3.getSeconds().toString();

    html += '<tr>';
    html += '   <td class="orders-order" style="text-align: center;">'+data.user_id+'</td>';
    html += '   <td class="orders-order" style="text-align: center;">'+data.parent_pax+'</td>';
    html += '   <td class="orders-order" style="text-align: center;">'+data.parent_csp+'</td>';
    html += '   <td class="orders-order" style="text-align: center;">'+data.withdraw_pax+'</td>';
    if(data.status == 1){ html += '   <td class="orders-order" style="text-align: center;">출금가능</td>'; }
    else{ html += '   <td class="orders-order" style="text-align: center;">출금불가</td>'; }
    html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
    html += '   <td class="orders-order" style="text-align: center;">'+time3+'</td>';
    html += '   <td class="orders-order" style="text-align: center;">';
    // html += '       <button class="btn btn-secondary btn-sm" style="padding: .2rem .75rem;" onclick=daily_receive('+data.pk_id+','+data.agent_id+');>공유보너스 받기</button>';
    if(data.status == 0){ html += '       <button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" disabled>심사중...</button>'; }
    else{ html += '       <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalMembers" style="padding: .2rem .75rem;" onclick=withdraw_action('+data.pk_id+');>출금하기</button>'; }
    html += '   </td>';
    html += '</tr>';
    $('#u_wallet_list').html(html);
}

// 사용자 user(u_join_bonus.html)
function u_join_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#u_join_list').html(html);
}

// 사용자 user(u_parent_bonus.html)
function u_parent_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#u_parent_list').html(html);
}

// 사용자 user(u_parent_matching_bonus.html)
function u_parent_matching_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#u_parent_matching_list').html(html);
}

// 사용자 user(u_order_history.html)
function u_order_history_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        html += '<tr>';
        if(data[i].t_status == 1){ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)</td>'; }
        else if(data[i].t_status == 2){ html += '   <td class="orders-order" style="text-align: center;">월렛카드</td>'; }
        else if(data[i].t_status == 3){ html += '   <td class="orders-order" style="text-align: center;">단말기</td>'; }
        else if(data[i].t_status == 4){ html += '   <td class="orders-order" style="text-align: center;">비자카드</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].product_price+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].order_num+'</td>';
        if(data[i].status == 1){ html += '   <td class="orders-order" style="text-align: center;">결제완료</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">미결제</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        if(data[i].status == 0){ html += '<button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=order_pay('+data[i].pk_id+');>구매하기</button>'; }
        else{ html += '--'; }
        html += '   </td>';
        html += '</tr>';
    }
    $('#u_order_history').html(html);
}

// 사용자 user(u_bank_withdraw.html)
function u_bank_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].paybank_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].paybank_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        if(data[i].status == 0){ html += '   <td class="orders-order" style="text-align: center;">심사중...</td>'; }
        else if(data[i].status == 1){ html += '   <td class="orders-order" style="text-align: center;">출금완료</td>'; }
        else if(data[i].status == 2){ html += '   <td class="orders-order" style="text-align: center;">출금실패</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">--</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].memo_lan+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '</tr>';
    }
    $('#u_bank_list').html(html);
}

// 사용자 user(u_marketing.html)
function u_marketing_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var img_path = "../data/imgthum/"+data[i].img_path;
        var content = data[i].content;
		if(content.length > 20){
			content = content.substr(0, 20) + "...";
        }

        html += '<li class="list-group-item px-0">';
        html += '   <div class="row align-items-center">';
        html += '       <div class="col-auto">';
        html += '           <a onclick=click_marketing('+i+') style="cursor:pointer;" data-toggle="modal" data-target="#modalMembers"><img src="'+img_path+'" alt="..." class="avatar-img rounded"></a>';
        html += '       </div>';
        html += '       <div class="col ml-n2">';
        html += '           <h4 class="card-title mb-1 name"><a onclick=click_marketing('+i+') style="cursor:pointer;" data-toggle="modal" data-target="#modalMembers">'+data[i].title+'</a></h4>';
        html += '           <p class="card-text small text-muted mb-1">'+time+'</p>';
        html += '           <p class="card-text small text-muted">'+content+'</p>';
        html += '       </div>';
        html += '       <div class="col-auto"><a onclick=zip_downloat('+i+') style="cursor:pointer;" class="btn btn-sm btn-white d-md-inline-block">다운로드</a></div>';
        html += '   </div>';
        html += '</li>';
    }
    $('#main_ul_id').html(html);
}

// 사용자 user(u_qa_content.html)
function u_qa_content_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var content_2 = data[i].content_2;
		if(content_2.length > 20){
			content_2 = content_2.substr(0, 20) + "...";
		}
		html += '<div class="card card-sm mb-3" data-toggle="modal" data-target="#modalKanbanTaskEmpty" onclick=click_qa('+i+');>';
		html += '	<div class="card-body">';
        html += '		<p class="mb-0">';
        if(data[i].status == 1){ html += '<strong>공지사항:'+data[i].title+'</strong> <span>내용:'+content_2+'</span>'; }
        else{ html += '			<strong>Q:'+data[i].title+'</strong> <span>A:'+content_2+'</span>'; }
		html += '		</p>';
		html += '	</div>';
		html += '</div>';
    }
    $('#main_content').html(html);
}

// 사용자 user(u_star_bonus.html)
function u_star_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#u_star_list').html(html);
}

// 사용자 user(u_business_tax.html)
function u_business_tax_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].total_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#u_business_tax_list').html(html);
}

// 사용자 user(u_card_list.html)
function u_card_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].pk_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].card_num+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].total_fee+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].memo_lan+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        html += '       <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalMembers2" style="padding: .2rem .75rem;" onclick=card_modify('+data[i].pk_id+',"'+data[i].card_num.replace(/ /gi, "")+'","'+data[i].memo_lan.replace(/ /gi, "")+'");>수정</button>';
        html += '       <button class="btn btn-danger btn-sm" style="padding: .2rem .75rem;" onclick="card_delete('+data[i].pk_id+');">삭제</button>';
        html += '   </td>';
        html += '</tr>';
    }
    $('#u_card_list').html(html);
}

// 사용자 user(u_store_list.html)
function u_store_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].store_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].store_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].store_account+'</td>';
        if(data[i].status == 2){ html += '   <td class="orders-order" style="text-align: center;">성공</td>'; }
        else if(data[i].status == 1){ html += '   <td class="orders-order" style="text-align: center;">실패</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">심사중..</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].total_fee+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        if(data[i].status == 1){ html += '       <button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=u_store_reexam('+data[i].pk_id+');>재심사</button>'; }
        else{ html += '--'; }
        html += '   </td>';
        html += '</tr>';
    }
    $('#u_store_list').html(html);
}

// 사용자 user(u_repurchase_bonus.html)
function u_repurchase_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].pk_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#u_repurchase_bonus').html(html);
}

///////////////////////////////////////////////////////////////////////////
// 관리자 admin(a_member_list.html)
function a_member_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        if(data[i].user_status == 1){ html += '   <td class="orders-order" style="text-align: center;">지사장</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">--</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].pk_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].branch_settings+'-'+data[i].memolan+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].idcard_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_mobile+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].p_uid+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_count+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_money_tow_1+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_money_tow_2+'</td>';
        if(data[i].payment_status == 1){ html += '   <td class="orders-order" style="text-align: center;">결제완료</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">미결제</td>'; }
        if(data[i].otp_sercet == 0){ html += '   <td class="orders-order" style="text-align: center;">미연동</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">연동완료</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        html += '       <button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=a_parent_list('+data[i].pk_id+');>추천인보기</button>';
        html += '   </td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        html += '       <button class="btn badge-warning btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        html += '           설정 하기';
        html += '       </button>';
        html += '       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 41px, 0px);">';
        html += '           <a class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalMembers" onclick=modify_information('+data[i].pk_id+',"'+data[i].user_name.replace(/ /gi, "")+'","'+data[i].user_mobile.replace(/ /gi, "")+'");>정보수정</a>';
        html += '           <a class="dropdown-item" href="javascript:reset_password('+data[i].pk_id+');">비밀번호초기화</a>';
        html += '           <a class="dropdown-item" href="javascript:reset_otp('+data[i].pk_id+');">OTP해제</a>';
        html += '           <a class="dropdown-item" href="javascript:reset_paybank('+data[i].pk_id+');">페이뱅크 UID해제</a>';
        html += '           <a class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalMembers" onclick=branch_setting('+data[i].pk_id+');>지사설정</a>'; 
        html += '       </div>';
        html += '   </td>';
        html += '</tr>';
    }
    $('#a_member_list').html(html);
}

// 관리자 admin(a_parent_details.html)
function a_parent_details_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        if(data[i].user_status == 1){ html += '   <td class="orders-order" style="text-align: center;">지사장</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">--</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].pk_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_mobile+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].p_uid+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_count+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_money_tow_1+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_money_tow_2+'</td>';
        if(data[i].payment_status == 1){ html += '   <td class="orders-order" style="text-align: center;">결제완료</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">미결제</td>'; }
        if(data[i].otp_sercet == 0){ html += '   <td class="orders-order" style="text-align: center;">미연동</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">연동완료</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        html += '       <button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=a_parent_list('+data[i].pk_id+');>추천인보기</button>';
        html += '   </td>';
        html += '</tr>';
    }
    $('#a_parent_details_list').html(html);
}

// 관리자 admin(a_support_details.html)
function a_support_details_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        if(data[i].user_status == 1){ html += '   <td class="orders-order" style="text-align: center;">지사장</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">--</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].pk_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_mobile+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].p_uid+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_count+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_id_1+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_id_2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_money_one_1+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].support_money_one_2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].left_level+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].right_level+'</td>';
        if(data[i].payment_status == 1){ html += '   <td class="orders-order" style="text-align: center;">결제완료</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">미결제</td>'; }
        if(data[i].otp_sercet == 0){ html += '   <td class="orders-order" style="text-align: center;">미연동</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">연동완료</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        html += '       <button class="btn btn-secondary btn-sm" style="padding: .2rem .75rem;" onclick=a_support_list('+data[i].pk_id+');>후원인보기</button>';
        html += '   </td>';
        html += '</tr>';
    }
    $('#a_support_details_list').html(html);
}

// 관리자 admin(a_assets_list.html)
function a_assets_append(data){
    var html = '';
    html += '<tr>';
    html += '   <td class="orders-order" style="text-align: center;">'+data.parent_pax+'</td>';
    html += '   <td class="orders-order" style="text-align: center;">'+data.parent_csp+'</td>';
    html += '   <td class="orders-order" style="text-align: center;">'+data.withdraw_pax+'</td>';
    html += '</tr>';
    $('#a_asset_managent').html(html);
}

// 관리자 admin(a_wallet_list.html)
function a_wallet_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date_2 = new Date(data[i].update_time * 1000);
        var time_2 = date_2.getFullYear().toString() + "-" + (date_2.getMonth() + 1).toString() + "-" + date_2.getDate().toString() + " " + date_2.getHours().toString() + ":" + date_2.getMinutes().toString() + ":" + date_2.getSeconds().toString();
        var date_3 = new Date(data[i].daily_time * 1000);
        var time_3 = date_3.getFullYear().toString() + "-" + (date_3.getMonth() + 1).toString() + "-" + date_3.getDate().toString() + " " + date_3.getHours().toString() + ":" + date_3.getMinutes().toString() + ":" + date_3.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_mobile+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_csp+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].withdraw_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].memo_lan+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time_2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time_3+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        html += '       <button class="btn badge-warning btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        html += '           설정 하기';
        html += '       </button>';
        html += '       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 41px, 0px);">';
        html += '           <a class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalMembers" onclick=memo_lan('+data[i].pk_id+',"'+data[i].memo_lan.replace(/ /gi, "")+'");>메모란</a>';
        html += '           <a class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalMembers" onclick=price_modify('+data[i].pk_id+','+data[i].parent_pax+');>PAX수정</a>';
        html += '       </div>';
        html += '   </td>';
        html += '</tr>';
    }
    $('#a_wallet_list').html(html);
}

// 관리자 admin(a_join_bonus.html)
function a_join_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].payer_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].payer_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_join_bonus_list').html(html);
}

// 관리자 admin(a_parent_bonus.html)
function a_parent_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].payer_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].payer_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_parent_bonus_list').html(html);
}

// 관리자 admin(a_parent_matching_bonus.html)
function a_parent_mat_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].payer_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].payer_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_parent_mat_bonus_list').html(html);
}

// 관리자 admin(a_order_history.html)
function a_order_history_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_mobile+'</td>';
        if(data[i].t_status == 1){ html += '   <td class="orders-order" style="text-align: center;">월렛카드(단품)</td>'; }
        else if(data[i].t_status == 2){ html += '   <td class="orders-order" style="text-align: center;">월렛카드</td>'; }
        else if(data[i].t_status == 3){ html += '   <td class="orders-order" style="text-align: center;">단말기</td>'; }
        else if(data[i].t_status == 4){ html += '   <td class="orders-order" style="text-align: center;">비자카드</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">--</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].order_num+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].product_price+'</td>';
        if(data[i].status == 1){ html += '   <td class="orders-order" style="text-align: center;">결제완료</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">미결제</td>'; }
        if(data[i].t_status == 1){ 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].receiving_number+'</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].receiving_address+'</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].color_id+'</td>'; 
        }else if(data[i].t_status == 2){ 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].receiving_number+'</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].receiving_address+'</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].color_id+'</td>'; 
        }else if(data[i].t_status == 3){ 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].receiving_number+'</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].receiving_address+'</td>';  
            html += '   <td class="orders-order" style="text-align: center;">--</td>'; 
        }else if(data[i].t_status == 4){ 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].receiving_number+'</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">'+data[i].receiving_address+'</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">--</td>'; 
        }else{ 
            html += '   <td class="orders-order" style="text-align: center;">--</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">--</td>'; 
            html += '   <td class="orders-order" style="text-align: center;">--</td>'; 
        }
        if(data[i].tt_status == 0){ html += '   <td class="orders-order" style="text-align: center;">배송중</td>'; }
        else if(data[i].tt_status == 1){ html += '   <td class="orders-order" style="text-align: center;">배송완료</td>'; }
        else if(data[i].tt_status == 2){ html += '   <td class="orders-order" style="text-align: center;">취소</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">--</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].memo_lan+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        html += '       <button class="btn badge-warning btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        html += '           설정 하기';
        html += '       </button>';
        html += '       <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 41px, 0px);">';
        html += '           <a class="dropdown-item" href="javascript:delivery_completed('+data[i].pk_id+');">배송완료</a>';
        html += '           <a class="dropdown-item" href="javascript:cancel_delivery('+data[i].pk_id+');">배송취소</a>';
        html += '           <a class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalMembers" onclick=memo_lan('+data[i].pk_id+',"'+data[i].memo_lan.replace(/ /gi, "")+'");>메모란</a>';
        html += '           <a class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalMembers" onclick=phone_change('+data[i].pk_id+',"'+data[i].receiving_number.replace(/ /gi, "")+'");>번호변경</a>';
        html += '           <a class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalMembers" onclick=address_change('+data[i].pk_id+',"'+data[i].receiving_address.replace(/ /gi, "")+'");>주소변경</a>';
        html += '       </div>';
        html += '   </td>';
        html += '</tr>';
    }
    $('#a_order_history_id').html(html);
}

// 관리자 admin(a_bank_withdraw.html)
function a_bank_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();

        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].paybank_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].paybank_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        if(data[i].status == 0){ html += '   <td class="orders-order" style="text-align: center;">심사중...</td>'; }
        else if(data[i].status == 1){ html += '   <td class="orders-order" style="text-align: center;">출금완료</td>'; }
        else if(data[i].status == 2){ html += '   <td class="orders-order" style="text-align: center;">출금실패</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">--</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].memo_lan+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        if(data[i].status == 0){
            html += ' <button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=paybank_bank_withdraw('+data[i].pk_id+');>출금하기</button> ';
            html += ' <button class="btn btn-secondary btn-sm" style="padding: .2rem .75rem;" data-toggle="modal" data-target="#modalMembers" onclick=withdraw_reject('+data[i].pk_id+');>출금거절</button>';
        }
        else if(data[i].status == 1){ html += '출금완료'; }
        else if(data[i].status == 2){ html += '출금실패'; }
        else{ html += '--'; }
        html += '   </td>';
        html += '</tr>';
    }
    $('#a_bank_list').html(html);
}

// 관리자 admin(a_marketing.html)
function a_marketing_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var img_data = "../data/imgthum/"+data[i].img_path;
        var content_2 = data[i].content_2;
        if(content_2.length > 20){ content_2 = content_2.substr(0, 20) + "..."; }
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].pk_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">';
        html += '       <img src="'+img_data+'" style="cursor:pointer;" data-toggle="modal" data-target="#modalMembers" onclick=marketing_imgclick("'+data[i].img_path.replace(/ /gi, "")+'");>';
        html += '   </td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].title+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+content_2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="text-muted sort" style="text-align: center;">';
        html += '       <button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=marketing_modifined('+data[i].pk_id+');>수정</button>';
        html += '       <button class="btn btn-danger btn-sm" style="padding: .2rem .75rem;" onclick=marketing_delete('+data[i].pk_id+');>삭제</button>';
        html += '   <td>';
        html += '</tr>';
    }
    $('#a_marketing_list').html(html);
}

// 관리자 admin(a_qa_content.html)
function a_qa_list_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var content_2 = data[i].content_2;
        if(content_2.length > 20){ content_2 = content_2.substr(0, 20) + "..."; }
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].pk_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].title+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+content_2+'</td>';
        if(data[i].status == 1){ html += '   <td class="orders-order" style="text-align: center;">공지사항</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">Q/A</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="text-muted sort" style="text-align: center;">';
        html += '       <button class="btn btn-primary btn-sm" style="padding: .2rem .75rem;" onclick=qa_modifined('+data[i].pk_id+');>수정</button>';
        html += '       <button class="btn btn-danger btn-sm" style="padding: .2rem .75rem;" onclick=qa_delete('+data[i].pk_id+');>삭제</button>';
        html += '   <td>';
        html += '</tr>';
    }
    $('#qa_list_id').html(html);
}

// 관리자 admin(a_star_bonus.html)
function a_star_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_star_bonus_list').html(html);
}

// 관리자 admin(a_vat_bonus.html)
function a_vat_bonus_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_vat_bonus_list').html(html);
}

// 관리자 admin(a_business_tax.html)
function a_business_tax_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var aaaa = (data[i].total_pax * 3.3 / 100).toFixed(8);
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].total_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].percent+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+aaaa+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_business_tax_list').html(html);
}

// 관리자 admin(a_withdraw_fee.html)
function a_withdraw_fee_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].paybank_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].paybank_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_withdraw_fee_list').html(html);
}

// 관리자 admin(a_card_list.html)
function a_card_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_mobile+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].card_num+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].total_fee+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].memo_lan+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_card_list').html(html);
}

// 관리자 admin(a_store_list.html)
function a_store_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        var date2 = new Date(data[i].update_time * 1000);
        var time2 = date2.getFullYear().toString() + "-" + (date2.getMonth() + 1).toString() + "-" + date2.getDate().toString() + " " + date2.getHours().toString() + ":" + date2.getMinutes().toString() + ":" + date2.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_mobile+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].store_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].store_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].store_account+'</td>';
        if(data[i].status == 2){ html += '   <td class="orders-order" style="text-align: center;">성공</td>'; }
        else if(data[i].status == 1){ html += '   <td class="orders-order" style="text-align: center;">실패</td>'; }
        else{ html += '   <td class="orders-order" style="text-align: center;">심사중..</td>'; }
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].total_fee+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time2+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_store_list').html(html);
}

// 관리자 admin(a_repurchase_bonus.html)
function a_repurchase_append(data){
    var html = '';
    for(var i=0; i<data.length; i++){
        var date = new Date(data[i].insert_time * 1000);
        var time = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + date.getDate().toString() + " " + date.getHours().toString() + ":" + date.getMinutes().toString() + ":" + date.getSeconds().toString();
        html += '<tr>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].agent_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_name+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].user_id+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+data[i].parent_pax+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">'+time+'</td>';
        html += '   <td class="orders-order" style="text-align: center;">--</td>';
        html += '</tr>';
    }
    $('#a_repurchase_bonus').html(html);
}