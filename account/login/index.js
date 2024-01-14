function init() {
    
}


function loginInputOnkeyup() {
    if(window.event.keyCode == 13) {
        loginSubmit();
    } else {
        const login_id = $("#login_id").val();
        const login_password = $("#login_password").val();
        if(login_id.length >= 5 && login_password.length >= 8) {
            $("#login_wrap .login_box .login_info_wrap .bottom_btn_wrap .login_btn").addClass("on");
        } else {
            $("#login_wrap .login_box .login_info_wrap .bottom_btn_wrap .login_btn").removeClass("on");
        }
    }
}

function loginSubmit() {
    loadingOn();
    var id = $("#login_id").val();
    if(id == '') {
        loadingOff();
        return myrecordAlert('on', '아이디를 입력해주세요');
    }
    var password = $("#login_password").val();
    if(password == '') {
        loadingOff();
        return myrecordAlert('on', '비밀번호를 입력해주세요');
    }

    $.ajax({
        async: false,
        type: "POST",
        data: JSON.stringify({
            id: id,
            password: password
        }),
        url: "/api/account/set.login_check.php",
        success: function(data) {
            loadingOff();
            if(data["code"] == "SUCCESS") {
                location.href = "/";
            } else {
                myrecordAlert('on', data["msg"]);
                return $("#myrecord_alert .alert_box .alert_btn").focus();
            }
        },
        error: function(error) {
            loadingOff();
            console.log(error);
        }
    })
}