function init() {
  stepRemote("step_1");
}

function stepRemote(name) {
  if (name != undefined) {
    $(".create_wrap .step_box").removeClass("on");
    $(".create_wrap .step_box[name=" + name + "]").addClass("on");
  }
}

/********** step_1 **********/

/***** 모두 동의 *****/
function termsAll() {
  $("#terms_service").prop("checked", true);
  $("#terms_private").prop("checked", true);
  $("#terms_marketing").prop("checked", true);

  step1Verify();
}
/***** 모두 동의 END *****/

/***** step_1 검증 *****/
function step1Verify() {
  var service = $("#terms_service").prop("checked");
  var private = $("#terms_private").prop("checked");
  if (service && private) {
    $(".create_wrap .step_box[name=step_1] .next_btn")
      .addClass("on")
      .attr("onclick", "stepRemote('step_2');");
  } else {
    $(".create_wrap .step_box[name=step_1] .next_btn")
      .removeClass("on")
      .attr("onclick", "myrecordAlert('on', '필수 약관에 동의해주세요');");
  }
}
/***** step_1 검증 END *****/

/********** step_1 END **********/

/********** step_2 **********/

/***** step_2 검증 onkeyup onchange *****/
function step2Verify() {
  var id = $("#account_id").val();
  var password = $("#account_password").val();
  var password_check = $("#account_password_check").val();
  var nickname = $("#account_nickname").val();

  if (id && password && password && password_check && nickname) {
    $(".create_wrap .step_box[name=step_2] .next_btn")
      .addClass("on")
      .attr("onclick", "createAccountCheck();");
  } else {
    $(".create_wrap .step_box[name=step_2] .next_btn")
      .removeClass("on")
      .attr("onclick", "myrecordAlert('on', '필수 입력값을 입력해 주세요')");
  }
}
/***** step_2 검증 onkeyup onchange END *****/

/***** step_2 검사 *****/
function createAccountCheck() {
  var id = $("#account_id").val();
  var password = $("#account_password").val();
  var password_check = $("#account_password_check").val();
  var nickname = $("#account_nickname").val();
  var name = $("#account_name").val();
  var phone = $("#account_phone").val();
  var email = $("#account_email").val();

  if (id.length < 5 || id.length > 20) {
    var alert_check = $("#account_id").siblings(".caution_text").length;
    if (alert_check == 0) {
      $("#account_id")
        .addClass("alert")
        .after('<p class="caution_text">아이디는 5~20자리로 입력해 주세요</p>');
    }
    $("#account_id").focus();
    return;
  }

  var password_reg =
    /^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^*&+=]).*$/;
  if (!password_reg.test(password)) {
    var alert_check = $("#account_password").siblings(".caution_text").length;
    if (alert_check == 0) {
      $("#account_password")
        .addClass("alert")
        .after(
          '<p class="caution_text">비밀번호 규칙에 맞게 입력해 주세요</p>'
        );
    }
    $("#account_password").focus();
    return;
  }

  if (password != password_check) {
    var alert_check = $("#account_password_check").siblings(
      ".caution_text"
    ).length;
    if (alert_check == 0) {
      $("#account_password_check")
        .addClass("alert")
        .after('<p class="caution_text">비밀번호가 일치하지 않습니다</p>');
    }
    $("#account_password_check").focus();
    return;
  }

  var nickname_reg = /^([a-zA-Z0-9ㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,10}$/;
  if (!nickname_reg.test(nickname)) {
    var alert_check = $("#account_nickname").siblings(".caution_text").length;
    if (alert_check == 0) {
      $("#account_nickname")
        .addClass("alert")
        .after('<p class="caution_text">닉네임 규칙에 맞게 입력해 주세요</p>');
    }
    $("#account_nickname").focus();
    return;
  }

  if (name != "") {
    var name_reg = /^([a-zA-Zㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,17}$/;
    if (!name_reg.test(name)) {
      var alert_check = $("#account_name").siblings(".caution_text").length;
      if (alert_check == 0) {
        $("#account_name")
          .addClass("alert")
          .after('<p class="caution_text">이름을 올바르게 입력해 주세요</p>');
      }
      $("#account_name").focus();
      return;
    }
  }

  if (phone != "") {
    var phone_reg = /^\d{3}-\d{3,4}-\d{4}$/;
    if (!phone_reg.test(phone)) {
      var alert_check = $("#account_phone").siblings(".caution_text").length;
      if (alert_check == 0) {
        $("#account_phone")
          .addClass("alert")
          .after(
            '<p class="caution_text">휴대폰번호 규칙에 맞게 입력해 주세요</p>'
          );
      }
      $("#account_phone").focus();
      return;
    }
  }

  if (email != "") {
    var email_reg =
      /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
    if (!email_reg.test(email)) {
      var alert_check = $("#account_email").siblings(".caution_text").length;
      if (alert_check == 0) {
        $("#account_email")
          .addClass("alert")
          .after('<p class="caution_text">이메일을 올바르게 입력해 주세요</p>');
      }
      $("#account_email").focus();
      return;
    }
  }

  createAccountSubmit();
}
/***** step_2 검사 END *****/

/********** step_2 END **********/

function createAccountSubmit() {
  var terms_marketing = $("#terms_marketing").prop("checked");
  var id = $("#account_id").val();
  var password = $("#account_password").val();
  var nickname = $("#account_nickname").val();
  var name = $("#account_name").val();
  var phone = $("#account_phone").val();
  var email = $("#account_email").val();
  var terms_marketing = $("#terms_marketing").prop("checked");

  $.ajax({
    type: "POST",
    data: JSON.stringify({
      terms_marketing: terms_marketing,
      account_id: id,
      account_password: password,
      account_nickname: nickname,
      account_name: name,
      account_phone: phone,
      account_email: email,
      terms_marketing: terms_marketing,
    }),
    url: "/api/account/set.create_account.php",
    success: function (data) {
      console.log(data);
      if (data["code"] == "SUCCESS") {
        myrecordAlert(
          "on",
          "회원가입이 완료되었습니다",
          "알림",
          "location.href='/'"
        );
      } else {
        myrecordAlert("on", data["msg"]);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}
