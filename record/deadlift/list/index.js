function init() {
    getRecordData();
}




function getRecordData() {
    loadingOn();
    var url = window.location.pathname;
    url = url.split('/');
    var record_type = url[2];
    var page = getParam('page');
    var search_key = getParam('search_key');
    var search_value = getParam('search_value');

    $.ajax({
        type: "POST",
        data: JSON.stringify({
            "record_type": record_type,
            "page": page,
            "search_key": search_key,
            "search_value": search_value
        }),
        url: "/api/record/get.record_board_list_data.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                var renderHtml = "";
                var listData = data["data"];
                for(key in data["data"]) {
                    renderHtml +=
                    '<div class="board_row">'+
                        '<div class="body_box writer"><a href="'+listData[key]['nickname']+'</div>'+
                        '<div class="body_box weight">'+listData[key]['record_weight']+'</div>'+
                        '<div class="body_box audit">'+listData[key]['record_status']+'</div>'+
                        '<div class="body_box date">'+listData[key]['date']+'</div>'+
                    '</div>';
                }

                $("#board_wrap .board_container .board_body_wrap").html(renderHtml);
                $("#search_key option[value="+search_key+"]").prop('selected', true);
                $("#search_keyword").val(search_value);
            } else {
                myrecordAlert('on', data["msg"]);
            }
            loadingOff();
        },
        error: function(error) {
            loadingOff();
            console.log(error);
        }
    })
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