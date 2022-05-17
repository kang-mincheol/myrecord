function init() {
    getRecordData();
}




function getRecordData() {
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
        },
        error: function(error) {
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