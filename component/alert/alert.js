function myrecordAlert(action, body, header, after, btnText) {
    if(action == undefined) {
        $("#myrecord_alert").removeClass("on");
        $("#myrecord_alert .alert_box .alert_header").html("알림");
        $("#myrecord_alert .alert_box .alert_body").html("");
        $("#myrecord_alert .alert_box .alert_btn").html("확인").attr('onclick', 'myrecordAlert();');
    } else {
        $("#myrecord_alert .alert_box .alert_body").html(body);

        if(body != undefined) {
            $("#myrecord_alert .alert_box .alert_body").html(body);
        }
        if(header != undefined) {
            $("#myrecord_alert .alert_box .alert_heder").html(header);
        }
        if(after != undefined) {
            $("#myrecord_alert .alert_box .alert_btn").attr('onclick', 'myrecord_alert(); '+after);
        }
        if(btnText != undefined) {
            $("#myrecord_alert .alert_box .alert_btn").html(btnText);
        }

        $("#myrecord_alert").addClass("on");
    }
}

function myrecordConfirm(action, body, confirm, confirmText, header) {
    if(action == undefined) {
        $("#myrecord_confirm").removeClass("on");
        $("#myrecord_confirm .confirm_box .confirm_header").html("확인");
        $("#myrecord_confirm .confirm_box .confirm_body").html("");
        $("#myrecord_confirm .confirm_box .confirm_btn").html("확인").attr('onclick', 'myrecordConfirm();');
    } else {
        $("#myrecord_confirm").html(body);

        if(body != undefined) {
            $("#myrecord_confirm .confirm_box .confirm_body").html(body);
        }
        if(confirm != undefined) {
            $("#myrecord_confirm .confirm_box .confirm_btn").attr('onclick', 'myrecordConfirm(); '+confirm);
        }
        if(confirmText != undefined) {
            $("#myrecord_confirm .confirm_box .confirm_btn_wrap .confirm_btn").html(confirmText);
        }
        if(header != undefined) {
            $("#myrecord_confirm .confirm_box .confirm_heder").html(header);
        }

        $("#myrecord_confirm").addClass("on");
    }
}