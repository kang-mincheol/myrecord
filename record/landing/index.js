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
        renderHtml +=
            '<div class="body_row'+addClass+'">'+
                '<div class="body_box rank"><p class="number">'+(parseInt(key) + 1)+'</p></div>'+
                '<div class="body_box total">'+data[key]["3대"]+'</div>'+
                '<div class="body_box squat">'+
                    (data[key]["squat_record_id"] != '-' ? '<a href="/record/record_view/?record_id='+data[key]["squat_record_id"]+'">' : '')+
                        data[key]["squat"]+
                    (data[key]["squat_record_id"] != '-' ? '</a>' : '')+
                '</div>'+
                '<div class="body_box benchpress">'+
                    (data[key]["benchpress_record_id"] != '-' ? '<a href="/record/record_view/?record_id='+data[key]["benchpress_record_id"]+'">' : '')+
                        data[key]["benchpress"]+
                    (data[key]["benchpress_record_id"] != '-' ? '</a>' : '')+
                '</div>'+
                '<div class="body_box deadlift">'+
                    (data[key]["deadlift_record_id"] != '-' ? '<a href="/record/record_view/?record_id='+data[key]["deadlift_record_id"]+'">' : '')+
                        data[key]["deadlift"]+'</div>'+
                    (data[key]["deadlift_record_id"] != '-' ? '</a>' : '')+
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
        renderHtml +=
            '<div class="body_row'+addClass+'">'+
                '<div class="body_box rank"><p class="number">'+(parseInt(key) + 1)+'</p></div>'+
                '<div class="body_box weight">'+
                    '<a href="/record/record_view/?record_id='+data[key]["record_id"]+'">'+
                        data[key]['weight']+
                    '</a>'+
                '</div>'+
                '<div class="body_box name">'+
                    '<a href="/record/record_view/?record_id='+data[key]["record_id"]+'">'+
                        data[key]['nickname']+
                    '</a>'+
                '</div>'+
            '</div>';
    }

    $("#record_ranking .ranking_contents_box[name="+type+"] .ranking_contents_body").html(renderHtml);

}