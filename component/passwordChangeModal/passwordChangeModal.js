const PasswordChangeModal = {
  handler: (action = true) => {
    if (action === true) {
      $("#passwordChangeModal").addClass("on");
    } else {
      $("#passwordChangeModal").removeClass("on");
      $("#now_password").val("");
      $("#new_password").val("");
      $("#new_password_check").val("");
      // 에러 메시지 초기화
      $("#passwordChangeModal .pcm_input").removeClass("alert");
      $("#passwordChangeModal .caution_text").remove();
    }
  },
  setPassword: () => {
    const nowPassword = $("#now_password").val();
    const newPassword = $("#new_password").val();
    const newPasswordCheck = $("#new_password_check").val();

    if (nowPassword === "") {
      $("#now_password")
        .addClass("alert")
        .after(`<p class="caution_text">비밀번호를 입력해 주세요</p>`);
      $("#now_password").focus();
      return;
    }

    const password_reg =
      /^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^*&+=]).*$/;

    if (!password_reg.test(newPassword)) {
      $("#new_password")
        .addClass("alert")
        .after(
          `<p class="caution_text">비밀번호 규칙에 맞게 입력해 주세요</p>`
        );
      $("#new_password").focus();
      return;
    }

    if (newPassword !== newPasswordCheck) {
      $("#new_password_check")
        .addClass("alert")
        .after(`<p class="caution_text">비밀번호가 일치하지 않습니다</p>`);
      $("#new_password_check").focus();
      return;
    }

    $.ajax({
      async: false,
      type: "POST",
      data: JSON.stringify({
        now_password: nowPassword,
        new_password: newPassword,
      }),
      url: "/api/account/set.password_change.php",
      success: (data) => {
        if (data["code"] === "SUCCESS") {
          myrecordAlert(
            "on",
            data["msg"],
            "",
            `PasswordChangeModal.handler(false);`
          );
        } else {
          myrecordAlert("on", data["msg"]);
        }
      },
      error: (error) => {
        console.log(error);
        myrecordAlert("on", "서버 오류가 발생했습니다", "알림", "");
      },
    });
  },
};

// 배경 클릭 시 모달 닫기
$(document).on("click", "#passwordChangeModal .pcm_overlay", function () {
  PasswordChangeModal.handler(false);
});
