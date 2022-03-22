function init() {
    rankingMenuRemote('squat');
}

function rankingMenuRemote(name) {
    if(name != undefined) {
        $("#record_ranking .ranking_menu_wrap .ranking_menu_btn").removeClass("on");
        $("#record_ranking .ranking_menu_wrap .ranking_menu_btn[name="+name+"]").addClass("on");

        $("#record_ranking .ranking_contents_box").removeClass("on");
        $("#record_ranking .ranking_contents_box[name="+name+"]").addClass("on");
    }
}