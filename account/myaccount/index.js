function init() {
    getAccountData();
}

function getAccountData() {
    loadingOn();

    $.ajax({
        type: "GET",
        url: "/api/account/get.myaccount_data.php",
        success: function(data) {
            loadingOff();
            if(data["code"] == "SUCCESS") {
                var account_data = data["data"];
                $("#account_id").val(account_data["account_id"]);
                $("#account_nickname").val(account_data["account_nickname"]);
                $("#account_name").val(account_data["account_name"]);
                $("#account_phone").val(account_data["account_phone"]);
                $("#account_email").val(account_data["account_email"]);
            } else {
                myrecordAlert('on', data["msg"]);
            }
        },
        error: function(error) {
            loadingOff();
            console.log(error);
        }
    });
}

function passwordChangeView() {
    myrecordAlert('on', '현재 준비중입니다');
}

function accountChangeCheck() {
    var nickname = $("#account_nickname").val();
    var name = $("#account_name").val();
    var phone = $("#account_phone").val();
    var email = $("#account_email").val();

    var nickname_reg = /^([a-zA-Z0-9ㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,10}$/;
    if(!nickname_reg.test(nickname)) {
        $("#account_nickname").addClass("alert").after('<p class="caution_text">닉네임 규칙에 맞게 입력해주세요</p>');
        return false;
    }

    if(name != "") {
        var name_reg = /^([a-zA-Zㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,17}$/;
        if(!name_reg.test(name)) {
            $("#account_name").addClass("alert").after('<p class="caution_text">이름 규칙에 맞게 입력해주세요</p>');
            return;
        }
    }

    if(phone != "") {
        var phone_reg = /^01([0|1|6|7|8|9])-?([0-9]{3,4})-?([0-9]{4})$/;
        if(!phone_reg.test(phone)) {
            $("#account_phone").addClass("alert").after('<p class="caution_text">핸드폰번호를 정확하게 입력해주세요</p>');
            return;
        }
    }

    if(email != "") {
        var email_reg = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
        if(!email_reg.test(email)) {
            $("#account_email").addClass("alert").after('<p class="caution_text">이메일을 정확하게 입력해주세요</p>');
            return;
        }
    }
}

function accountChangeCheck2() {
    var nickname = $("#account_nickname").val();
    var name = $("#account_name").val();
    var phone = $("#account_phone").val();
    var email = $("#account_email").val();

    var nickname_reg = /^([a-zA-Z0-9ㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,10}$/;
    if(!nickname_reg.test(nickname)) {
        $("#account_nickname").addClass("alert").after('<p class="caution_text">닉네임 규칙에 맞게 입력해주세요</p>');
        return false;
    }

    if(name != "") {
        var name_reg = /^([a-zA-Zㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,17}$/;
        if(!name_reg.test(name)) {
            $("#account_name").addClass("alert").after('<p class="caution_text">이름 규칙에 맞게 입력해주세요</p>');
            return false;
        }
    }

    if(phone != "") {
        var phone_reg = /^01([0|1|6|7|8|9])-?([0-9]{3,4})-?([0-9]{4})$/;
        if(!phone_reg.test(phone)) {
            $("#account_phone").addClass("alert").after('<p class="caution_text">핸드폰번호를 정확하게 입력해주세요</p>');
            return false;
        }
    }

    if(email != "") {
        var email_reg = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
        if(!email_reg.test(email)) {
            $("#account_email").addClass("alert").after('<p class="caution_text">이메일을 정확하게 입력해주세요</p>');
            return false;
        }
    }

    return true;
}

function myaccountChange() {
    loadingOn();
    var check = accountChangeCheck2();

    if(check) {
        var data = {};
        var nickname = $("#account_nickname").val();
        var name = $("#account_name").val();
        var phone = $("#account_phone").val();
        var email = $("#account_email").val();
        data.nickname = nickname;
        if(name != "") {
            data.name = name;
        }
        if(phone != "") {
            data.phone = phone;
        }
        if(email != "") {
            data.email = email;
        }

        $.ajax({
            type: "POST",
            data: JSON.stringify(data),
            url: "/api/account/set.myaccount_change.php",
            success: function(data) {
                loadingOff();
                console.log(data);
                if(data["code"] == "SUCCESS") {
                    myrecordAlert('on', data["msg"]);
                } else {
                    myrecordAlert('on', data["msg"]);
                }
            },
            error: function(error) {
                loadingOff();
                console.log(error);
            }
        });
    }
}