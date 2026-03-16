function confirmDelete(logId) {
  myrecordConfirm(
    "on",
    "이 득근일지를 삭제하시겠습니까?<br/>삭제 후 복구가 불가능합니다",
    () => {
      deleteLog(logId);
    },
    "삭제 확인",
  );
}

function deleteLog(logId) {
  $.ajax({
    url: "/api/workout_log/set.workout_log_delete.php",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ log_id: logId }),
    success: function (res) {
      if (res.code === "SUCCESS") {
        location.href = "/workout_log/list/";
      } else {
        myrecordAlert("on", res.msg || "오류가 발생했습니다", "알림", "");
      }
    },
    error: function () {
      myrecordAlert("on", "서버 오류가 발생했습니다", "알림", "");
    },
  });
}
