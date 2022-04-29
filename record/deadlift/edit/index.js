function init() {
    //셀렉트박스 사용시 아래함수를 호출할것
    selectDeviceCheck();
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