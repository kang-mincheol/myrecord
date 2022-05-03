function init() {
    //등록 수정 체크
    recordEditCheck();

    //셀렉트박스 사용시 아래함수를 호출할것
    selectDeviceCheck();
}





function recordEditCheck() {
    var record_id = getParam('record_id');
    record_id = record_id.replace(/[^0-9]/g, "");

    if(record_id == '') {
        //등록
    } else {
        //수정
        $(".edit_wrap .footer_btn_wrap .update_btn").text('수정');
        getRecordData(record_id);
    }
}

function getRecordData(record_id) {
    $.ajax({
        type: "POST",
        async: false,
        data: JSON.stringify({
            record_id: record_id
        }),
        url: "",
        success: function(data) {
            console.log(data);
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function setRecordData() {
    var record_id = getParam('record_id');
    record_id = record_id.replace(/[^0-9]/g, "");
    if(record_id == '') {
        insertData();
    } else {
        updateData();
    }
}

function insertData() {
    
}

function updateData() {
    
}






function fileAdd() {
    var fileLength = $(".file_wrap .file_add_wrap .file_row_box .file_row").length;
    var rowNumber = fileLength + 1;

    var rowHtml = 
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
    var fileName = obj.files[0].name;
//    console.log(fileName);
    $(obj).siblings('.file_name_box').text(fileName);
}

function getParam(name) {

    var params = location.search.substr(location.search.indexOf("?") + 1);
    var value = "";
    params = params.split("&");

    for (var i = 0; i < params.length; i++) {
        temp = params[i].split("=");
        if ([temp[0]] == name) { value = temp[1]; }
    }

    return value;
}