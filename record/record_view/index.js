function init() {
    getRecordData();
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
        type: "POST",
        url: "/api/record/set.record_delete.php",
        data: JSON.stringify({
            record_id: record_id
        }),
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
        type: "POST",
        url: "/api/record/get.record_view_data.php",
        data: JSON.stringify({
            record_id: record_id
        }),
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
