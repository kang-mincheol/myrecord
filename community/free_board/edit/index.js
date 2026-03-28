const pageInit = async () => {
  const id = getParam("id");

  if (id === "") {
    // 등록
  } else {
    // 수정
    await getEditData(id);
  }

  loadingOff();
};

const getEditData = async (id) => {
  try {
    const url = `/api/v1/boards/${id}/edit`;
    const response = await fetch(url, {
      method: "GET",
    })
      .then((response) => response)
      .then((response) => response.json());

    if (response["code"] === "SUCCESS") {
      const data = response["data"];

      $("#board_title").val(data.title);
      requestEditors[0].exec("SET_CONTENTS", [data.contents]);

      // 목록 버튼 -> 취소 버튼 으로 변경
      $("#editor_wrap .bottom_btn_wrap .list_btn").attr("onclick", "editCancel()").text("취소");

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
    url: "/api/v1/boards",
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
    title,
    contents,
  };

  $.ajax({
    async: false,
    type: "PUT",
    data: JSON.stringify(data),
    url: "/api/v1/boards/" + id,
    success: (data) => {
      console.log("data => ", data);
      if (data["code"] === "SUCCESS") {
        myrecordAlert('on', data["msg"], '알림', `location.href='/community/free_board/view/?id=${id}';`);
      } else {
        myrecordAlert('on', data["msg"]);
      }
    },
    error: (error) => {
      console.log("error => ", error);
      myrecordAlert('on', '자유게시판 수정에 실패했습니다.');
    }
  });
};

const goFreeBoardList = () => {
  if (document.referrer.includes("/community/free_board/list/")) {
    history.back();
  } else {
      location.href = "/community/free_board/list/";
  }
};

const editCancel = () => {
  const id = getParam("id");
  location.href = `/community/free_board/view/?id=${id}`;
}