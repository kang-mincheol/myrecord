const submitPost = () => {
  const title = document.getElementById("board_title").value;
  requestEditors.getById["board_editor"].exec("UPDATE_CONTENTS_FIELD", []);
  const contents = document.getElementById("board_editor").value;

  const data = {
    title,
    contents,
  };

  $.ajax({
    async: false,
    type: "POST",
    data: JSON.stringify(data),
    url: "/api/free_board/set.free_board_insert.php",
    success: (data) => {
      console.log(data);
      if (data["code"] === "SUCCESS") {
        myrecordAlert(
          "on",
          data["msg"],
          "",
          `location.href='/community/free_board/view?id=${data["board_id"]}';`
        );
      } else {
        myrecordAlert("on", data["msg"]);
      }
    },
    error: (error) => {
      console.log(error);
      myrecordAlert(
        "on",
        `글 등록에 실패했습니다.<br/>고객센터에 문의해 주세요.`
      );
    },
  });
};

const goList = () => {
  if (history) {
    history.back();
    return;
  }

  location.href = "/community/free_board/edit";
};
