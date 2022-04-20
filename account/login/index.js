function init() {
    
}


function loginSubmit() {
    var id = $("#login_id").val();
    if(id == '') {
        return myrecordAlert('on', '아이디를 입력해주세요');
    }
    var password = $("#login_password").val();
    if(password == '') {
        return myrecordAlert('on', '비밀번호를 입력해주세요');
    }

    $.ajax({
        type: "POST",
        data: JSON.stringify({
            id: id,
            password: password
        }),
        url: "/api/account/set.login_check.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                location.href = "/";
            } else {
                return myrecordAlert('on', data["msg"]);
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
}