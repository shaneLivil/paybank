// 联系人转账
function contact_out(message, j_pass, j_coin_id){
    // 1. message.paybank_id - (받는사람 APP UID)
    // 2. message.paybank_name - (받는사람 APP 계정)
    // 3. message.parent_pax - (전송 코인수량)
    // 4. j_pass - (거래 비밀번호)
    // 5. j_coin_id - (거래코인 5.PAX, 4.USDT)
    // 6. message.token - (보내는 사람 token값)
    // 7. message.random_key - (보내는 사람 random_key) 

    var parent_pax = message.parent_pax - 2;
    parent_pax = (parent_pax - (parent_pax * 3.3 / 100)).toFixed(8);
    var password = md5(j_pass);
    password = md5(password);
    var param = '{"coinId": '+j_coin_id+',"mobile": "'+message.paybank_name+'","amount": "'+parent_pax+'","password": "'+password+'"}';
    const object = window.btoa(param);
    const time = Date.parse(new Date()) / 1000;
    var sign = md5(time + object + message.random_key);
    var headers = {
        "Content-Type":"application/json;charset=UTF-8",
        "Accept-Language": "zh-CN",
        "Authorization": "Bearer " + message.token,
        "Client-Type": "1"
    };
    var data = {
        "object" : object,
        "sign" : sign,
        "ts":time
    };
    $.ajax({
        headers: headers,
        url: "https://rest.payurl.app/transfer/contact/out",
        contentType: "application/json", //必须有
        dataType: "json", //表示返回值类型，不必须
        data:  JSON.stringify(data),//传递数据
        type: "post",
        success: function (data) {
            var json_data = data;
            if(json_data.code == 200){
                // 성공
                update_withdraw(message);
            }else if(json_data.code == 201){
                // 서버에러
                alert("금액이 부족하거나 서버 에러 입니다.");
            }else if(json_data.code == 203){
                // 암호화 실패 
                alert("암호화 실패 하였습니다 관리자에게 문의주세요.");
            }else if(json_data.code == 401){
                // 토큰 시간 만료
                alert("토큰 시간이 만료 되었습니다.");
            }else if(json_data.code == 402){
                // 토큰 인증 실패
                alert("토큰인증이 실패 하였습니다.");
            }else if(json_data.code == 403){
                // 로그인이 안된 상태 입니다.
                alert("로그인이 안된 상태 입니다.");
            }else if(json_data.code == 404){
                // 다른기기에서 로그인 하였습니다.
                alert("다른 기기에서 로그인 되였습니다.");
            }else if(json_data.code == 204){
                // 금액이 부족 합니다.
                alert("금액이 부족 합니다.");
            }else{
                // 다른 에러
                alert("거래실패 잠시후 다시 시도하세요.");
            }
        },
        error: function(error){
            console.log(JSON.stringify(error));
            alert("Error..." + error);
        }
    });
}

function update_withdraw(j_message){
    var shouxufei = j_message.parent_pax - 2;             // 출금 수수료 뺀금액
    var shouxu_3 = shouxufei * 3.3 / 100;                 // 3.3 뺀금액
    var parent_pax = shouxufei - shouxu_3;                // 실제 출금 금액

    var data = {
        result: 'a_update_withdraw',
        shouxufei: shouxufei,
        shouxu_3: shouxu_3,
        parent_pax: parent_pax,
        j_pk_id: j_message.pk_id,
        j_agent_id: j_message.agent_id,
        j_paybank_id: j_message.paybank_id,
        j_paybank_name: j_message.paybank_name,
        j_parent_pax: j_message.parent_pax
    }

    $.ajax({
        type: 'POST',
        url: '../send_db.php',
        dataType: 'text',
        data: data,
        success: function(data){
            var json_data = JSON.parse(data);
            if(json_data.code == 1){
                alert("출금 전송 완료.");
                a_bank(1);
            }else{
                alert("서버 에러. 잠시후 다시 시도하세요.");
            }
        },
        error: function(err){
            alert('error: ' + JSON.stringify(err));
        }
    });
}