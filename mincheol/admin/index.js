function init() {
    getTotalData();
}

function getTotalData() {
    loadingOn();
    $.ajax({
        type: "GET",
        url: "/mincheol/api/get.team_data.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                if(data["data"]["team"]) {
                    var team = data["data"]["team"];
                    var team_html = "";
                    var score_html = "";
                    var person_html = "";
                    for(var key in team) {
                        team_html +=
                        '<div class="myrecord_input_wrap">'+
                            '<div class="label_box">'+
                                '<label class="wrap_label" for="team_name_'+team[key]["id"]+'">'+(parseInt(key)+1)+' 팀명</label>'+
                            '</div>'+
                            '<div class="form_value_box">'+
                                '<input id="team_name_'+team[key]["id"]+'" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text" value="'+team[key]["team_name"]+'"/>'+
                            '</div>'+
                        '</div>';

                        score_html +=
                        '<div class="myrecord_input_wrap">'+
                            '<div class="label_box">'+
                                '<label class="wrap_label" for="team_score_'+team[key]["id"]+'">'+team[key]["team_name"]+' 팀</label>'+
                            '</div>'+
                            '<div class="form_value_box">'+
                                '<input id="team_score_'+team[key]["id"]+'" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number" value="0"/>'+
                            '</div>'
                        '</div>';

                        person_html +=
                        '<div class="team_colum">'+
                            '<div class="myrecord_input_wrap">'+
                                '<div class="label_box">'+
                                    '<label class="wrap_label" for="add_person_'+team[key]["id"]+'">'+team[key]["team_name"]+' 팀</label>'+
                                '</div>'+
                                '<div class="form_value_box">'+
                                    '<input id="add_person_'+team[key]["id"]+'" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text"/>'+
                                '</div>'+
                            '</div>'+
                            '<button class="add_btn" onclick="setTeamPerson('+team[key]["id"]+')">추가</button>'+
                        '</div>';
                        
                    }
                    
                    $("#team_admin_wrap .team_master_wrap").html(team_html);
                    $("#team_score_wrap .team_score_wrap").html(score_html);
                    $("#team_person_wrap .team_box").html(person_html);
                }
                
                if(data["data"]["score"] && data["data"]["score"].length > 0) {
                    var score = data["data"]["score"];
                    for(var key in score) {
                        $("#team_score_"+score[key]["team_id"]).val(score[key]["team_score"]);
                    }
                }

                if(data["data"]["person"] && data["data"]["person"].length > 0) {
                    var person = data["data"]["person"];
                    var person_html = "";
                    console.log(person);

                    for(var key in person) {
                        person_html +=
                        '<div class="person_box" person_id="'+person[key]["id"]+'" onclick="deletePerson('+person[key]["id"]+');">'+person[key]["person_name"]+'</div>';
                    }

                    $("#team_person_delete .person_wrap").html(person_html);
                }
            } else {
                myrecordAlert('on', data["msg"]);
            }
            loadingOff();
        },
        error: function(error) {
            myrecordAlert('on', error);
        }
    });
}

function setTeamMaster() {
    var teamData = {};
    for (var i = 1; i <= 3; i++) {
        var teamName = $("#team_name_"+i).val();
        if(teamName == '') {
            myrecordAlert('on', "팀 이름을 입력해주세요");
            return;
        }
        teamData[i] = teamName;
    }

    $.ajax({
        type: "POST",
        data: JSON.stringify(teamData),
        url: "/mincheol/api/set.team_master.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                location.reload();
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function setTeamScore() {
    var scoreData = {};
    var length = $("#team_score_wrap .team_score_wrap .myrecord_input_wrap").length;
    for (var i = 1; i <= length; i++) {
        var teamScore = $("#team_score_"+i).val();
        if(teamScore == '') {
            myrecordAlert('on', "팀 스코어를 입력해주세요");
            return;
        }
        scoreData[i] = teamScore;
    }

    $.ajax({
        type: "POST",
        data: JSON.stringify(scoreData),
        url: "/mincheol/api/set.team_score.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                location.reload();
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function setTeamPerson(team) {
    var personName = $("#add_person_"+team).val();
    if(personName == '') {
        myrecordAlert('on', '이름을 입력해주세요');
        return;
    }
    console.log(personName);

    $.ajax({
        type: "POST",
        data: JSON.stringify({
            team_id: team,
            person: personName
        }),
        url: "/mincheol/api/set.team_person.php",
        success: function(data) {
            console.log(data);
            if(data["code"] == "SUCCESS") {
                location.reload();
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function deletePerson(number) {
    var name = $(".person_box[person_id="+number+"]").text();
    console.log(name);

    if(confirm(name+"(을)를 삭제하시겠습니까?")) {
        $.ajax({
            type: "POST",
            data: JSON.stringify({
                person_id: number
            }),
            url: "/mincheol/api/set.team_person_delete.php",
            success: function(data) {
                console.log(data);
                if(data["code"] == "SUCCESS") {
                    location.reload();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
}