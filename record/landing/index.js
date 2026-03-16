function init() {
    rankingMenuRemote('total');

    getTotalRankingData();

    getRecordRanking('Squat');
    getRecordRanking('BenchPress');
    getRecordRanking('DeadLift');
}

function rankingMenuRemote(name) {
    if(name != undefined) {
        $("#record_ranking .ranking_menu_wrap .ranking_menu_btn").removeClass("on");
        $("#record_ranking .ranking_menu_wrap .ranking_menu_btn[name="+name+"]").addClass("on");

        $("#record_ranking .ranking_contents_box").removeClass("on");
        $("#record_ranking .ranking_contents_box[name="+name+"]").addClass("on");
    }
}

function getTotalRankingData() {
    loadingOn();
    $.ajax({
        async: false,
        type: "GET",
        url: "/api/record/landing/get.record_total_ranking.php",
        success: function(data) {
            loadingOff();
            if(data["code"] == "SUCCESS") {
                totalRankingRender(data["data"]);
            } else if (data["code"] == "EMPTY") {
                //EMPTY
                var ele = '<div class="empty_box">'+data["msg"]+'</div>';
                $("#record_ranking .ranking_contents_box[name=total] .ranking_contents_body").html(ele);
            } else {
                myrecordAlert('on', data["msg"]);
            }
        },
        error: function(error) {
            loadingOff();
            console.log(error);
        }
    })
}

function totalRankingRender(data) {
    var renderHtml = "";
    for(key in data) {
        var addClass = "";
        if(key >= 0 && key <= 2) {
            addClass = " top";
        }

        var squatVal =
            '<span class="col_label">S</span>' +
            (data[key]["squat_record_id"] != '-' ? '<a href="/record/record_view/?record_id='+data[key]["squat_record_id"]+'">' : '') +
                (data[key]["squat"] > 0 ? data[key]["squat"] + ' kg' : '-') +
            (data[key]["squat_record_id"] != '-' ? '</a>' : '');

        var benchVal =
            '<span class="col_label">B</span>' +
            (data[key]["benchpress_record_id"] != '-' ? '<a href="/record/record_view/?record_id='+data[key]["benchpress_record_id"]+'">' : '') +
                (data[key]["benchpress"] > 0 ? data[key]["benchpress"] + ' kg' : '-') +
            (data[key]["benchpress_record_id"] != '-' ? '</a>' : '');

        var deadVal =
            '<span class="col_label">D</span>' +
            (data[key]["deadlift_record_id"] != '-' ? '<a href="/record/record_view/?record_id='+data[key]["deadlift_record_id"]+'">' : '') +
                (data[key]["deadlift"] > 0 ? data[key]["deadlift"] + ' kg' : '-') +
            (data[key]["deadlift_record_id"] != '-' ? '</a>' : '');

        renderHtml +=
            '<div class="body_row'+addClass+'">'+
                '<div class="body_box rank"><p class="number">'+(parseInt(key) + 1)+'</p></div>'+
                '<div class="body_box total">'+data[key]["3대"]+' kg</div>'+
                '<div class="body_box squat">'+squatVal+'</div>'+
                '<div class="body_box benchpress">'+benchVal+'</div>'+
                '<div class="body_box deadlift">'+deadVal+'</div>'+
                '<div class="body_box name">'+data[key]["nickname"]+'</div>'+
            '</div>';
    }

    $("#record_ranking .ranking_contents_box[name=total] .ranking_contents_body").html(renderHtml);
}

function getRecordRanking(name) {
    loadingOn();
    $.ajax({
        async: false,
        type: "POST",
        data: JSON.stringify({
            record_type: name
        }),
        url: "/api/record/landing/get.record_ranking.php",
        success: function(data) {
            loadingOff();
            if(data["code"] == "SUCCESS") {
                if(data["data"]) {
                    recordRankingRender(name, data["data"]);
                }
            } else if(data["code"] == "EMPTY") {
                //EMPTY
                var ele = '<div class="empty_box">'+data["msg"]+'</div>';
                $("#record_ranking .ranking_contents_box[name="+name+"] .ranking_contents_body").html(ele);
            } else {
                myrecordAlert('on', data["msg"]);
            }
        },
        error: function(error) {
            loadingOff();
            console.log(error);
        }
    });
}

function recordRankingRender(type, data) {
    var renderHtml = "";

    for(key in data) {
        var addClass = "";
        if(key >= 0 && key <= 2) {
            addClass = " top";
        }
        var recordId = data[key]["record_id"];
        renderHtml +=
            '<div class="body_row'+addClass+' clickable" onclick="location.href=\'/record/record_view/?record_id='+recordId+'\';">'+
                '<div class="body_box rank"><p class="number">'+(parseInt(key) + 1)+'</p></div>'+
                '<div class="body_box weight">'+data[key]['weight']+' kg</div>'+
                '<div class="body_box name">'+data[key]['nickname']+'<i class="fa-solid fa-chevron-right row_arrow"></i></div>'+
            '</div>';
    }

    $("#record_ranking .ranking_contents_box[name="+type+"] .ranking_contents_body").html(renderHtml);

}