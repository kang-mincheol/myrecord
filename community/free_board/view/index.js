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
    },
    error: (error) => {
      console.log(error);
    },
  });
};
