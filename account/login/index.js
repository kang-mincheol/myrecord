function init() {
    
}


function loginInputOnkeyup() {
    if(window.event.keyCode == 13) {
        loginSubmit();
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
            console.log(data);
            if(data["code"] == "SUCCESS") {
                location.href = "/";
            } else {
                return myrecordAlert('on', data["msg"]);
            }
        },
        error: function(error) {
            loadingOff();
            console.log(error);
        }
    })
}