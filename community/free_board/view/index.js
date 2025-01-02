const init = () => {
  getFreeBoardViewData();
};

const getFreeBoardViewData = () => {
  const boardId = getParam("id");
  console.log("board_id => ", boardId);

  $.ajax({
    async: false,
    type: "POST",
    data: JSON.stringify({
      boardId: boardId,
    }),
    url: "/api/free_board/get.free_board_view_data.php",
    success: (response) => {
      console.log("response => ", response);
      if (response["code"] === "SUCCESS") {
        console.log(1);
        $("#view_header_wrap #contents_title").text(response.data.title);
        $("#view_header_wrap .writer_value").text(response.data.user_nickname);
        $("#view_header_wrap .write_date").text(response.data.create_date);
        $("#view_contents_wrap").html(response.data.contents);
      } else {
        myrecordAlert("on", data["msg"]);
      }
    },
    error: (error) => {
      console.log(error);
    },
  });
};

const goFreeBoardList = () => {
  if (history.length > 1) {
    history.back();
  } else {
    location.href = "/community/free_board/list";
  }
};
