const pageInit = async () => {
  let pageIndex = getParam("page");
  if (pageIndex === undefined || pageIndex < 1) {
    pageIndex = 1;
  }

  listInfo.pageIndex = pageIndex;

  await getFreeBoardList();
};

const getFreeBoardList = async () => {
  loadingOn();
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
        renderFreeBoardList(data["data"]["list"]);
        renderFreeBoardPage(data["data"]["page"]);
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
  if (list === undefined || list.length === 0) {
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

const renderFreeBoardPage = (page) => {
  const blockSize = 5;

  let currentPage = listInfo.pageIndex;

  if (currentPage > page.totalPage) {
    currentPage = page.totalPage;
  }

  const pages = getPageBlock(currentPage, page.totalPage, blockSize);
  const pageRenderHtml = getPageBlockHtml(currentPage, page.totalPage, pages);

  $("#pagingWrap").html(pageRenderHtml);
}

const movePage = async (pageIndex) => {
  listInfo.pageIndex = pageIndex;
  updateParam("page", listInfo.pageIndex);
  await getFreeBoardList();
}

const listSearch = () => {
  getFreeBoardList();
}

const moveFreeBoardView = (id) => {
  location.href = `/community/free_board/view?id=${id}`;
}