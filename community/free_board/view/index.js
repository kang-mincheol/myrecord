const init = () => {
  getFreeBoardViewData();
  loadComments();
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
          $("#view_wrap .bottom_btn_wrap .delete_btn")
            .removeClass("off")
            .attr("onclick", `deleteBoard(${boardId})`);
        }
      } else {
        myrecordAlert("on", response["msg"]);
      }
    },
    error: (error) => {
      console.log(error);
    },
  });
};

const goFreeBoardList = () => {
  if (document.referrer.includes("/community/free_board/list/")) {
    history.back();
  } else {
    location.href = "/community/free_board/list/";
  }
};

const goFreeBoardEdit = (id) => {
  location.href = `/community/free_board/edit/?id=${id}`;
};

const deleteBoard = (boardId) => {
  myrecordConfirm(
    "on",
    "게시글을 삭제하시겠습니까?",
    () => {
      $.ajax({
        type: "POST",
        url: "/api/free_board/set.free_board_delete.php",
        contentType: "application/json",
        data: JSON.stringify({ board_id: boardId }),
        success: (res) => {
          if (res.code === "SUCCESS") {
            myrecordAlert("on", "게시글이 삭제되었습니다.", "알림", "location.href='/community/free_board/list/';");
          } else {
            myrecordAlert("on", res.msg || "오류가 발생했습니다.", "알림", "");
          }
        },
        error: () => {
          myrecordAlert("on", "서버 오류가 발생했습니다.", "알림", "");
        },
      });
    },
    "삭제 확인"
  );
};


/* ── 댓글 ── */

const loadComments = () => {
  const boardId = getParam("id");

  $.ajax({
    type: "GET",
    url: "/api/free_board/get.free_board_comment_list.php",
    data: { board_id: boardId },
    success: (res) => {
      if (res.code === "SUCCESS") {
        $("#comment_count").text(res.data.count);
        renderComments(res.data.list);
      }
    },
    error: (err) => {
      console.log(err);
    },
  });
};

const renderComments = (list) => {
  if (!list || list.length === 0) {
    $("#comment_list").html(
      '<div class="comment_empty"><i class="fa-regular fa-comment-dots"></i>아직 댓글이 없습니다.</div>'
    );
    return;
  }

  let html = "";
  list.forEach((c) => {
    html +=
      `<div class="comment_item">` +
        `<div class="comment_avatar"><i class="fa-solid fa-user"></i></div>` +
        `<div class="comment_body">` +
          `<div class="comment_meta">` +
            `<span class="comment_nickname">${escHtml(c.user_nickname)}</span>` +
            `<span class="comment_date">${c.create_datetime}</span>` +
          `</div>` +
          `<p class="comment_contents">${escHtml(c.contents)}</p>` +
        `</div>` +
        (c.is_mine
          ? `<button class="comment_delete_btn" onclick="deleteComment(${c.id});" title="삭제"><i class="fa-solid fa-xmark"></i></button>`
          : "") +
      `</div>`;
  });

  $("#comment_list").html(html);
};

const submitComment = () => {
  const boardId  = getParam("id");
  const contents = $("#comment_textarea").val().trim();

  if (!contents) {
    myrecordAlert("on", "댓글 내용을 입력해주세요.", "알림", "");
    return;
  }

  const $btn = $(".comment_submit_btn");
  $btn.prop("disabled", true);

  $.ajax({
    type: "POST",
    url: "/api/free_board/set.free_board_comment_insert.php",
    contentType: "application/json",
    data: JSON.stringify({ board_id: boardId, contents: contents }),
    success: (res) => {
      $btn.prop("disabled", false);
      if (res.code === "SUCCESS") {
        $("#comment_textarea").val("");
        $("#comment_input_count").text("0");
        loadComments();
      } else {
        myrecordAlert("on", res.msg || "오류가 발생했습니다.", "알림", "");
      }
    },
    error: () => {
      $btn.prop("disabled", false);
      myrecordAlert("on", "서버 오류가 발생했습니다.", "알림", "");
    },
  });
};

const deleteComment = (commentId) => {
  myrecordConfirm(
    "on",
    "댓글을 삭제하시겠습니까?",
    () => {
      $.ajax({
        type: "POST",
        url: "/api/free_board/set.free_board_comment_delete.php",
        contentType: "application/json",
        data: JSON.stringify({ comment_id: commentId }),
        success: (res) => {
          if (res.code === "SUCCESS") {
            loadComments();
          } else {
            myrecordAlert("on", res.msg || "오류가 발생했습니다.", "알림", "");
          }
        },
        error: () => {
          myrecordAlert("on", "서버 오류가 발생했습니다.", "알림", "");
        },
      });
    },
    "삭제 확인"
  );
};

const commentInputCount = (el) => {
  $("#comment_input_count").text(el.value.length);
};

const escHtml = (str) => {
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/\n/g, "<br>");
};
