function init() {
    getRecordData();
    loadComments();
}

function prev() {
    history.back();
}

function requestDelete() {
    var record_id = getParam('record_id');
    if(record_id == '' || isNaN(record_id)) {
        myrecordAlert('on', '잘못된 값입니다');
        return;
    }

    $.ajax({
        type: "DELETE",
        url: "/api/v1/records/" + record_id,
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                myrecordAlert('on', '삭제가 완료되었습니다', '알림', 'history.back();');
            } else {
                myrecordAlert('on', data["msg"]);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function getRecordData() {
    loadingOn();
    var record_id = getParam('record_id');
    record_id = record_id.replace(/[^0-9]/g, "");

    $.ajax({
        type: "GET",
        url: "/api/v1/records/" + record_id,
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                // 닉네임
                $("#view_nickname").text(data["data"]["record_nickname"]);
                // 등록일자
                $("#view_date").text(data["data"]["record_create"]);
                // 종목
                $("#view_record_name").text(data["data"]["record_name"]);
                // 무게
                $("#view_record_weight").text(data["data"]["record_weight"] + " kg");
                // 상태 배지
                var statusEng = data["data"]["record_status_eng"];
                $("#view_status_badge").addClass(statusEng).text(data["data"]["record_status"]);

                // 메모
                if(data["data"]["record_memo"]) {
                    $("#view_memo_text").text(data["data"]["record_memo"]);
                    $("#view_memo_wrap").show();
                }

                if(data["file"]) {
                    var fileHtml = "";
                    data["file"].forEach(function(file) {
                        if(file["file_type"].indexOf('image') !== -1) {
                            fileHtml +=
                                '<div class="item">' +
                                    '<img class="file_img" src="' + file["file_src"] + '"/>' +
                                '</div>';
                        } else if(file["file_type"].indexOf('video') !== -1) {
                            fileHtml +=
                                '<div class="item">' +
                                    '<video controls class="file_video">' +
                                        '<source src="' + file["file_src"] + '" type="' + file["file_type"] + '">' +
                                    '</video>' +
                                '</div>';
                        }
                    });

                    $(".file_slide_wrap").html(fileHtml);
                    setTimeout(function() {
                        fileSlideInit();
                        loadingOff();
                    }, 500);
                } else {
                    loadingOff();
                }

                // 본인일 경우
                if(data["data"]["is_recorder"]) {
                    // 승인 완료 - 인증서 버튼 표시
                    if(statusEng == "approval") {
                        $("#certificate_wrap").show();
                        $("#certificate_save").attr('href', '/record/record_certificate/?record_id=' + record_id);
                    }
                    // 삭제 버튼 표시
                    $("#right_btn_wrap").show();
                    $("#delete_btn").attr('onclick', 'requestDelete();');
                }
            } else {
                loadingOff();
                myrecordAlert('on', data["msg"]);
            }
        },
        error: function(error) {
            loadingOff();
            console.log(error);
        }
    });
}

function fileSlideInit() {
    $('.owl-carousel').owlCarousel({
        loop: false,
        margin: 10,
        items: 1,
        center: true,
        autoHeight: true,
        nav: true
    });
}


/* ── 댓글 ── */

function loadComments() {
    var recordId = getParam('record_id');

    $.ajax({
        type: "GET",
        url: "/api/v1/records/" + recordId + "/comments",
        success: function(res) {
            if (res.code === "SUCCESS") {
                $("#comment_count").text(res.data.count);
                renderComments(res.data.list);
            }
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function renderComments(list) {
    if (!list || list.length === 0) {
        $("#comment_list").html(
            '<div class="comment_empty"><i class="fa-regular fa-comment-dots"></i>아직 댓글이 없습니다.</div>'
        );
        return;
    }

    var html = "";
    list.forEach(function(c) {
        html +=
            '<div class="comment_item">' +
                '<div class="comment_avatar"><i class="fa-solid fa-user"></i></div>' +
                '<div class="comment_body">' +
                    '<div class="comment_meta">' +
                        '<span class="comment_nickname">' + escHtml(c.user_nickname) + '</span>' +
                        '<span class="comment_date">' + c.create_datetime + '</span>' +
                    '</div>' +
                    '<p class="comment_contents">' + escHtml(c.contents) + '</p>' +
                '</div>' +
                (c.is_mine
                    ? '<button class="comment_delete_btn" onclick="deleteComment(' + c.id + ');" title="삭제"><i class="fa-solid fa-xmark"></i></button>'
                    : '') +
            '</div>';
    });

    $("#comment_list").html(html);
}

function submitComment() {
    var recordId = getParam('record_id');
    var contents = $("#comment_textarea").val().trim();

    if (!contents) {
        myrecordAlert('on', '댓글 내용을 입력해주세요.', '알림', '');
        return;
    }

    var $btn = $(".comment_submit_btn");
    $btn.prop("disabled", true);

    $.ajax({
        type: "POST",
        url: "/api/v1/records/" + recordId + "/comments",
        contentType: "application/json",
        data: JSON.stringify({ contents: contents }),
        success: function(res) {
            $btn.prop("disabled", false);
            if (res.code === "SUCCESS") {
                $("#comment_textarea").val("");
                $("#comment_input_count").text("0");
                loadComments();
            } else {
                myrecordAlert('on', res.msg || '오류가 발생했습니다.', '알림', '');
            }
        },
        error: function() {
            $btn.prop("disabled", false);
            myrecordAlert('on', '서버 오류가 발생했습니다.', '알림', '');
        }
    });
}

function deleteComment(commentId) {
    var recordId = getParam('record_id');
    myrecordConfirm(
        "on",
        "댓글을 삭제하시겠습니까?",
        function() {
            $.ajax({
                type: "DELETE",
                url: "/api/v1/records/" + recordId + "/comments/" + commentId,
                success: function(res) {
                    if (res.code === "SUCCESS") {
                        loadComments();
                    } else {
                        myrecordAlert('on', res.msg || '오류가 발생했습니다.', '알림', '');
                    }
                },
                error: function() {
                    myrecordAlert('on', '서버 오류가 발생했습니다.', '알림', '');
                }
            });
        },
        "삭제 확인"
    );
}

function commentInputCount(el) {
    $("#comment_input_count").text(el.value.length);
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/\n/g, "<br>");
}
