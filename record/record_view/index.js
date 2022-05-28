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