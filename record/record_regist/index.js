function init() {
    //등록 수정 체크
//    recordEditCheck();

    //셀렉트박스 사용시 아래함수를 호출할것
    selectDeviceCheck();
}

function prev() {
    history.back();
}

function noticePopupOff() {
    $("#notice_popup_wrap").removeClass("on");
}



function recordEditCheck() {
    return;
    let record_id = getParam('record_id');
    record_id = record_id.replace(/[^0-9]/g, "");

    if(record_id != '') {
        //수정
        $(".edit_wrap .edit_title").text('Record 수정');
        $(".edit_wrap .footer_btn_wrap .update_btn").text('수정');
        getRecordData(record_id);   
    }
}

function getRecordData(record_id) {
    return;
    $.ajax({
        type: "POST",
        async: false,
        data: JSON.stringify({
            record_id: record_id
        }),
        url: "/api/record/get.record_edit_data.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                $("#record_type").attr('onclick', '');
                $(".myrecord_input_wrap .myrecord_select_wrap .mobile_select_wrap .mobile_select option").prop('disabled', true);
                $(".myrecord_input_wrap .myrecord_select_wrap .mobile_select_wrap .mobile_select option[value="+data["data"]["type"]+"]").prop('disabled', false);

                if(data["data"]["status"] != 0) {
                    $("#record_weight").attr('readonly', true);
                }

                let record_data = data["data"];
                $(".myrecord_input_wrap.record_type .myrecord_select_wrap .select_list_wrap .select_list_btn[value="+record_data["type"]+"]").click();
                $("#record_weight").val(record_data["weight"]);

                let fileRenderHtml = "";
                for(key in record_data["file"]) {
                    fileRenderHtml +=
                        '<div class="file_row" file_no="'+record_data["file"][key]["file_no"]+'">'+
                            '<div class="file_name_box">'+record_data["file"][key]["file_name"]+'</div>'+
                            '<div class="file_row_remote_box">'+
                                '<a class="file_view_btn" href="/data/record/'+record_data["file"][key]["file_id"]+'" target="_blank">파일보기</a>'+
                            '</div>'+
                        '</div>';
                }

                $(".myrecord_input_wrap.file_wrap .file_row_box").append(fileRenderHtml);
            } else {
                myrecordAlert('on', data["msg"], '알림', 'location.href=\'/record/landing/\'');
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function setRecordData() {
    let record_id = getParam('record_id');
    record_id = record_id.replace(/[^0-9]/g, "");
    if(record_id == '') {
        insertData();
    } else {
//        updateData();
    }
}

function insertData() {
    loadingOn();
    let record_type = $("#record_type").val();
    record_type = record_type.replace(/[^0-9]/g, "");
    let record_weight = $("#record_weight").val();
    record_weight = record_weight.replace(/[^0-9]/g, "");

    if(record_type == 'none' || record_type == '') {
        myrecordAlert('on', '등록할 종목을 선택해주세요');
        loadingOff();
        return;
    }

    if(record_weight == '') {
        myrecordAlert('on', '무게를 입력해주세요');
        loadingOff();
        return;
    }

    let fileCheck = $(".myrecord_input_wrap.file_wrap .file_add_wrap .file_row_box .file_row").length;
    if(fileCheck == 0) {
        myrecordAlert('on', '파일을 첨부해주세요');
        loadingOff();
        return;
    }

    let recordData = new FormData();

    let totalFileSize = 0;
    let fileCount = 0;
    for(let i = 1; i <= fileCheck; i++) {
        let thisFile = document.querySelector(".myrecord_input_wrap.file_wrap .file_add_wrap .file_row_box input[name=file_"+i+"]").files[0];
        if(thisFile != '') {
            totalFileSize += parseInt(thisFile.size);
            if(thisFile != undefined) {
                fileCount++;
                recordData.append('record_file_'+i, thisFile);
            }
        }
    }

    let totalFileSizeCal = totalFileSize / 1024 / 1024;
    let fileLimitSize = 100;

    if(totalFileSizeCal > fileLimitSize) {
        myrecordAlert('on', '파일은 총 100MB 이하로 업로드 해주세요');
        loadingOff();
        return;
    }

    if(fileCount == 0) {
        myrecordAlert('on', '파일을 등록해주세요');
        loadingOff();
        return;
    }

    recordData.append('record_type', record_type);
    recordData.append('record_weight', record_weight);


    $.ajax({
        async: false,
        type: "POST",
        data: recordData,
        url: "/api/record/set.record_insert.php",
        contentType: false,
        processData: false,
        success: function(data) {
            loadingOff();
            console.log(data);
            if(data["code"] == "SUCCESS") {
                myrecordAlert('on', '등록 완료', '알림', 'location.href=\'/record/my_record/\'');
            } else {
                myrecordAlert('on', data["msg"]);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function updateData() {
    myrecordAlert('on', '사용불가능한 기능입니다');
    return;
//    loadingOn();
//    let record_id = getParam('record_id');
//    record_id = record_id.replace(/[^0-9]/g, "");
//
//    if(record_id == "") {
//        myrecordAlert('on', '올바르지 않은 값입니다');
//        loadingOff();
//        return;
//    }
//
//    let recordData = new FormData();
//
//    loadingOff();
}






function fileAdd() {
    let fileLength = $(".file_wrap .file_add_wrap .file_row_box .file_row").length;
    let rowNumber = fileLength + 1;

    let rowHtml = 
        '<div class="file_row">' +
            '<input name="file_'+rowNumber+'" type="file" onchange="fileChange(this)"/>'+
            '<div class="file_name_box">파일없음</div>'+
            '<div class="file_row_remote_box">'+
                '<button class="file_select_btn" onclick="fileSelect(this);">선택</button>'+
                '<button class="file_delete_btn" onclick="fileDelete(this);">삭제</button>'+
            '</div>'+
        '</div>';

    $(".file_wrap .file_add_wrap .file_row_box").append(rowHtml);
}


function fileSelect(obj) {
    $(obj).parent('.file_row_remote_box').siblings('input[type=file]').click();
}

function fileDelete(obj) {
    $(obj).parent('.file_row_remote_box').parent('.file_row').remove();
}

function fileChange(obj) {
    let fileAccessType = ["mp4", "m4v", "avi", "wmv", "mwa", "asf", "mpg", "mpeg", "mkv", "mov", "3gp", "3g2", "webm", "jpeg", "jpg", "png", "HEIC", "HEIF", "HEVC", "image/jpeg", "video/quicktime"];
    let fileSize = obj.files[0].size;
    fileSize = fileSize / 1024 / 1024;
    let fileLimit = 100;
    if(fileSize > fileLimit) {
        myrecordAlert('on', '파일 크기는 100MB 이하로 업로드 해주세요');
        return;
    }
    let fileType = obj.files[0].type;

    let typeCheck = false;
    for(key in fileAccessType) {
        if(fileType.includes(fileAccessType[key])) {
            typeCheck = true;
        }
    }

    if(!typeCheck) {
        $(obj).val('');
        myrecordAlert('on', '지원하지 않는 파일확장자 입니다<br/>파일은 동영상만 첨부가능합니다<br/>동영상 파일이 업로드가 안될경우 고객센터에 문의해주세요');
        return;
    }

    let fileName = obj.files[0].name;
    $(obj).siblings('.file_name_box').text(fileName);
}

function getParam(name) {

    let params = location.search.substr(location.search.indexOf("?") + 1);
    let value = "";
    params = params.split("&");

    for (let i = 0; i < params.length; i++) {
        temp = params[i].split("=");
        if ([temp[0]] == name) { value = temp[1]; }
    }

    return value;
}

function fileView(obj) {
    let file_id = $(obj).attr('file_id');
    
}