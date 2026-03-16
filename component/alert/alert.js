function myrecordAlert(action, body, header, after, btnText) {
  if (action == undefined) {
    $("#myrecord_alert").removeClass("on");
    $("#myrecord_alert .alert_box .alert_header").html("알림");
    $("#myrecord_alert .alert_box .alert_body").html("");
    $("#myrecord_alert .alert_box .alert_btn")
      .html("확인")
      .attr("onclick", "myrecordAlert();");
  } else {
    $("#myrecord_alert .alert_box .alert_body").html(body);

    if (body != undefined) {
      $("#myrecord_alert .alert_box .alert_body").html(body);
    }
    if (header != undefined) {
      $("#myrecord_alert .alert_box .alert_header").html(header);
    }
    if (after != undefined) {
      $("#myrecord_alert .alert_box .alert_btn").attr(
        "onclick",
        "myrecordAlert(); " + after,
      );
    }
    if (btnText != undefined) {
      $("#myrecord_alert .alert_box .alert_btn").html(btnText);
    }

    $("#myrecord_alert").addClass("on");
  }
}

function myrecordConfirm(action, body, confirm, confirmText, header) {
  if (action == undefined) {
    $("#myrecord_confirm").removeClass("on");
    $("#myrecord_confirm .confirm_box .confirm_header").html("확인");
    $("#myrecord_confirm .confirm_box .confirm_body").html("");
    $("#myrecord_confirm .confirm_box .confirm_btn")
      .html("확인")
      .off("click")
      .on("click", function() { myrecordConfirm(); });
    $("#myrecord_confirm .confirm_box .confirm_btn_wrap .cancel_btn")
      .off("click")
      .on("click", function() { myrecordConfirm(); });
  } else {
    if (body != undefined) {
      $("#myrecord_confirm .confirm_box .confirm_body").html(body);
    }
    if (header != undefined) {
      $("#myrecord_confirm .confirm_box .confirm_header").html(header);
    }
    if (confirmText != undefined) {
      $("#myrecord_confirm .confirm_box .confirm_btn_wrap .confirm_btn").html(confirmText);
    }

    var $confirmBtn = $("#myrecord_confirm .confirm_box .confirm_btn");
    $confirmBtn.off("click");
    if (typeof confirm === "function") {
      $confirmBtn.on("click", function() { myrecordConfirm(); confirm(); });
    } else if (confirm != undefined) {
      $confirmBtn.on("click", function() { myrecordConfirm(); eval(confirm); });
    } else {
      $confirmBtn.on("click", function() { myrecordConfirm(); });
    }

    $("#myrecord_confirm").addClass("on");
  }
}

$(document).on("click", "#myrecord_confirm", function(e) {
  if (!$(e.target).closest(".confirm_box").length) {
    myrecordConfirm();
  }
});
