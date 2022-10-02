function init() {
    getLiveData();
}

function getLiveData() {
    loadingOn();
    $.ajax({
        type: "GET",
        url: "/mincheol/api/get.live_data.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                var team = data["team"];
                var team_html = "";
                for(var key in team) {
                    team_html +=
                    '<div class="team_colum" name="'+team[key]["id"]+'">'+
                        '<div class="team_score_name">SCORE</div>'+
                        '<div class="team_score">'+team[key]["team_score"]+'</div>'+
                        '<div class="team_name">'+team[key]["team_name"]+'팀</div>'+
                        '<div class="team_person_box">'+
                        '</div>'+
                    '</div>';
                }
                $("#team_wrap").html(team_html);

                if(data["person"] && data["person"].length > 0) {
                    var person = data["person"];
                    for(var key in person) {
                        var person_html =
                        '<div class="team_person_name">'+person[key]["person_name"]+'</div>';
                        
                        $("#team_wrap .team_colum[name="+person[key]["team_id"]+"] .team_person_box").append(person_html);
                    }
                    
                }
            }
            
            loadingOff();
        },
        error: function(error) {
            console.log(error);
            loadingOff();
        }
    });
}