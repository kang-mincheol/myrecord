function init() {
    
}


function loginSubmit() {
    var id = $("#login_id").val();
    var password = $("#login_password").val();

    $.ajax({
        type: "POST",
        data: JSON.stringify({
            id: id,
            password: password
        }),
        url: "/api/account/set.login_check.php",
        success: function(data) {
            console.log(data);
        },
        error: function(error) {
            console.log(error);
        }
    })
}