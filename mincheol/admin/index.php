<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/mincheol/admin/index.css');
?>


<div id="team_admin_wrap" class="admin_wrap">
    <p class="wrap_title">팀명 관리</p>
    <div class="team_master_wrap">
<!--
        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="team_name_1">1 팀명</label>
            </div>
            <div class="form_value_box">
                <input id="team_name_1" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text"/>
            </div>
        </div>
        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="team_name_2">2 팀명</label>
            </div>
            <div class="form_value_box">
                <input id="team_name_2" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text"/>
            </div>
        </div>
        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="team_name_3">3 팀명</label>
            </div>
            <div class="form_value_box">
                <input id="team_name_3" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text"/>
            </div>
        </div>
-->
    </div>
    <div class="remote_btn_wrap">
        <button class="remote_btn" onclick="setTeamMaster();">저장</button>
    </div>
</div>


<div id="team_score_wrap" class="admin_wrap">
    <p class="wrap_title">스코어 관리</p>
    
    <div class="team_score_wrap">
        
<!--
        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="team_score_1">병준 팀</label>
            </div>
            <div class="form_value_box">
                <input id="team_score_1" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number"/>
            </div>
        </div>
        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="team_score_2">용훈 팀</label>
            </div>
            <div class="form_value_box">
                <input id="team_score_2" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number"/>
            </div>
        </div>
        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="team_score_3">재석 팀</label>
            </div>
            <div class="form_value_box">
                <input id="team_score_3" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number"/>
            </div>
        </div>
-->
        
    </div>
    
    <div class="remote_btn_wrap">
        <button class="remote_btn" onclick="setTeamScore();">저장</button>
    </div>
</div>


<div id="team_person_wrap" class="admin_wrap">
    <p class="wrap_title">팀원 추가</p>
    <div class="team_box">
        
<!--
        <div class="team_colum" name="1">
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="add_person_1">재석 팀</label>
                </div>
                <div class="form_value_box">
                    <input id="add_person_1" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text"/>
                </div>
            </div>
            <button class="add_btn">추가</button>
        </div>
        <div class="team_colum" name="1">
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="add_person_2">재석 팀</label>
                </div>
                <div class="form_value_box">
                    <input id="add_person_2" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text"/>
                </div>
            </div>
            <button class="add_btn">추가</button>
        </div>
        <div class="team_colum" name="1">
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="add_person_3">재석 팀</label>
                </div>
                <div class="form_value_box">
                    <input id="add_person_3" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text"/>
                </div>
            </div>
            <button class="add_btn">추가</button>
        </div>
-->

    </div>
</div>


<div id="team_person_delete" class="admin_wrap">
    <p class="wrap_title">팀원 삭제</p>
    <div class="person_wrap">
        
    </div>
</div>












<?
echo script_load('/mincheol/admin/index.js');
?>
<script>
$(function() {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>