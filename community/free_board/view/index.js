const init = () => {
  getFreeBoardViewData();
};

const getFreeBoardViewData = () => {
  const boardId = getParam("id");

  $.ajax({
    async: false,
    type: "POST",
    data: JSON.stringify({
      boardId: boardId,
    }),
    url: "/api/free_board/get.free_board_view_data.php",
    success: (response) => {
      if (response["code"] === "SUCCESS") {
        $("#view_header_wrap #contents_title").text(response.data.title);
        $("#view_header_wrap .writer_value").text(response.data.user_nickname);
        $("#view_header_wrap .write_date").text(response.data.create_date);
        $("#view_contents_wrap").html(response.data.contents);

        if (response.data.is_write === true) {
          // 작성자 본인 체크
          $("#view_wrap .bottom_btn_wrap .list_btn").removeClass("on");
          $("#view_wrap .bottom_btn_wrap .edit_btn")
            .removeClass("off")
            .attr("onclick", `goFreeBoardEdit(${boardId})`);
        }
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

const goFreeBoardEdit = (id) => {
  location.href = `/community/free_board/edit/?id=${id}`;
};
