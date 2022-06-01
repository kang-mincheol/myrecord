function init() {
    rankingMenuRemote('total');

    getTotalRankingData();
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
        type: "GET",
        url: "/api/record/landing/get.record_total_ranking.php",
        success: function(data) {
            loadingOff();
            console.log(data);
            totalRankingRender(data["data"]);
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
        renderHtml +=
            '<div class="body_row">'+
                '<div class="body_box rank">'+(parseInt(key) + 1)+'</div>'+
                '<div class="body_box total">'+data[key]["3대"]+'</div>'+
                '<div class="body_box squat">'+data[key]["squat"]+'</div>'+
                '<div class="body_box benchpress">'+data[key]["benchpress"]+'</div>'+
                '<div class="body_box deadlift">'+data[key]["deadlift"]+'</div>'+
                '<div class="body_box name">'+data[key]["nickname"]+'</div>'+
            '</div>';
    }

    $("#record_ranking .ranking_contents_box[name=total] .ranking_contents_body").html(renderHtml);
}