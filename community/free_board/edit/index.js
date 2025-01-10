const pageInit = () => {
  const id = getParam("id");

  console.log("id => ", id);
  if (id === "") {
    // 등록
  } else {
    // 수정
    getEditData(id);
  }
};

const getEditData = async (id) => {
  try {
    const url = `/api/free_board/get.free_board_edit_data.php?id=${id}`;
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id: id,
      }),
    })
      .then((response) => response)
      .then((response) => response.json());

    console.log("response => ", response);
    if (response["code"] === "SUCCESS") {
      const data = response["data"];

      $("#board_title").val(data.title);
      requestEditors[0].exec("SET_CONTENTS", [data.contents]);

      // 버튼 수정으로 변경
      $("#editor_wrap .bottom_btn_wrap .edit_btn")
        .attr("onclick", "updateBoard()")
        .text("수정");
    } else {
      myrecordAlert("on", response["msg"]);
    }
  } catch (error) {
    console.log(error);
    myrecordAlert(
      "on",
      "자유게시판 수정 데이터를 불러오는 중 에러가 발생했습니다."
    );
  }
};

const insertBoard = () => {
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

const updateBoard = () => {
  const id = getParam("id");
  const title = document.getElementById("board_title").value;
  requestEditors.getById["board_editor"].exec("UPDATE_CONTENTS_FIELD", []);
  const contents = document.getElementById("board_editor").value;

  const data = {
    id,
    title,
    contents,
  };
};

const goList = () => {
  if (history) {
    history.back();
    return;
  }

  location.href = "/community/free_board/edit";
};
