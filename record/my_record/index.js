function init() {
    getMyRecordData();
}

function getMyRecordData() {
    $.ajax({
        type: "GET",
        url: "/api/record/get.my_record_data.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                var renderHtml = "";
                for(key in data["data"]) {
                    var thisData = data["data"][key];
                    renderHtml +=
                        '<div class="record_box">'+
                            '<div class="box_title">'+thisData["type_name"]+'</div>'+
                            '<div class="record_info_box">';

                    if(thisData["status"]) {
                        renderHtml +=
                            '<div class="record_weight">'+thisData["weight"]+'</div>'+
                            '<div class="record_status">'+
                                '<i class="fa-solid fa-circle-check '+thisData["status_color"]+'"></i>'+
                                '<p class="status_text">'+thisData["status"]+'</p>'+
                            '</div>';
                    } else {
                        renderHtml +=
                            '<p class="empty_line">기록 없음</p>';
                    }
                    
                    renderHtml +=
                            '</div>'+
                        '</div>';
                }

                $("#my_record_wrap .record_wrap").append(renderHtml);
            } else {
                myrecordAlert('on', data["msg"], '알림', 'location.href=\'/\'');
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}