const pageInit = () => {
  getFreeBoardList();
};

const getFreeBoardList = async () => {
  const param = {
    pageIndex: listInfo.pageIndex,
    pageRow: listInfo.pageRow,
  };

  const searchKeyword = $("#search_keyword").val();

  if (searchKeyword !== "") {
    param["searchKey"] = $("#search_key").val();
    param["searchValue"] = searchKeyword;
  }

  $.ajax({
    type: "POST",
    data: JSON.stringify(param),
    url: "/api/free_board/get.free_board_list_data.php",
    success: (data) => {
      loadingOff();
      if (data["code"] === "SUCCESS") {
        renderFreeBoardList(data["data"]);
      }
    },
    error: (error) => {
      loadingOff();
      console.log(error);
    },
  });
};

const renderFreeBoardList = (list) => {
  console.log("list => ", list);
};
