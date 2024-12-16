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
  let renderHtml = "";
  if (list.length === 0) {
    renderHtml = `
      <div class="empty-text-box">
        검색결과가 없습니다.
      </div>`;
  } else {
    renderHtml = list.reduce((acc, data) => {
      acc += `
        <div class="board_row" onclick="moveFreeBoardView(${data.id});">
          <div class="top_box">
            <div class="body_box title">${data.title}</div>
          </div>
          <div class="bottom_box">
            <div class="body_box writer">${data.nickname}</div>
            <div class="body_box view">${data.view_count}</div>
            <div class="body_box date">${data.write_date}</div>
          </div>
        </div>
      `;
      return acc;
    }, "");
  }

  $("#board_wrap .board_container .board_body_wrap").html(renderHtml);
};

const listSearch = () => {
  getFreeBoardList();
}

const moveFreeBoardView = (id) => {
  location.href = `/community/free_board/view?id=${id}`;
}