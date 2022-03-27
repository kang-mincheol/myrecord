function myrecordAlert(action, body, header, after, btnText) {
    if(action == undefined && body == undefined) {
        $("#myrecord_alert").removeClass("on");
        $("#myrecord_alert .alert_box .alert_header").html("알림");
        $("#myrecord_alert .alert_box .alert_body").html("");
        $("#myrecord_alert .alert_box .alert_btn").html("확인").attr('onclick', 'myrecord_alert();');
    } else {
        $("#myrecord_alert .alert_body").html(body);

        if(header != undefined) {
            $("#myrecord_alert .alert_heder").html(header);
        }
        if(after != undefined) {
            $("#myrecord_alert .alert_btn").attr('onclick', 'myrecord_alert(); '+after);
        }
        if(btnText != undefined) {
            $("#myrecord_alert .alert_btn").html(btnText);
        }

        $("#myrecord_alert").addClass("on");
    }
}