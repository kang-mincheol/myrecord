function init() {
    getRecordData();
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
            loadingOff();
            console.log(data);
            if(data["code"] == "SUCCESS") {
                //닉네임
                $("#view_wrap .nickname_row .nickname_box .value").text(data["data"]["record_nickname"]);
                //등록일자
                $("#view_wrap .nickname_row .create_date .value").text(data["data"]["record_create"]);

                //종목
                $("#view_wrap .record_info_row .info_box.type_box .value_box .value").text(data["data"]["record_name"]);
                //무게
                $("#view_wrap .record_info_row .info_box.weight_box .value_box .value").text(data["data"]["record_weight"]);
                //상태
                $("#view_wrap .record_info_row .info_box.status_box").addClass(data["data"]["record_status_eng"]);
                $("#view_wrap .record_info_row .info_box.status_box .value_box .value").text(data["data"]["record_status"]);

                if(data["file"]){
                    //파일
                    var fileHtml = "";
                    data["file"].forEach(function(data) {
                        if(data["file_type"].indexOf('image') !== -1) {
                            fileHtml +=
                            '<div class="item">'+
                                '<img class="file_img" src="'+data["file_src"]+'"/>'+
                            '</div>';
                        } else if(data["file_type"].indexOf('video') !== -1) {
                            fileHtml +=
                            '<div class="item">'+
                                '<video controls class="file_video">'+
                                    '<source src="'+data["file_src"]+'" type="'+data["file_type"]+'">'+
                                '</video>'+
                            '</div>';
                        }
                    });

                    $(".file_row .file_slide_wrap").html(fileHtml);

                    fileSlideInit();
                }

                //인증서 view
                if(data["data"]["is_recorder"]) {
                    $("#certificate_save").addClass("on").attr('href', '/record/record_certificate/?record_id='+record_id);
                }
            }
            
        },
        error: function(error) {
            loadingOff();
            console.log(error);
        }
    })
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