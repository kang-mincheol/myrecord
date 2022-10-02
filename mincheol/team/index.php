<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/mincheol/team/team.css');
?>


<div id="team_wrap">
    
<!--
    <div class="team_colum" name="1">
        <div class="team_score_name">SCORE</div>
        <div class="team_score">0</div>
        <div class="team_name">병준팀</div>
        <div class="team_person_box">
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
        </div>
    </div>

    <div class="team_colum" name="2">
        <div class="team_score_name">SCORE</div>
        <div class="team_score">0</div>
        <div class="team_name">용훈팀</div>
        <div class="team_person_box">
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
        </div>
    </div>

    <div class="team_colum" name="3">
        <div class="team_score_name">SCORE</div>
        <div class="team_score">0</div>
        <div class="team_name">재석팀</div>
        <div class="team_person_box">
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
            <div class="team_person_name">강민철</div>
        </div>
    </div>
-->
    
    
    
</div>


<?
echo script_load('/mincheol/team/team.js');
?>
<script>
$(function() {
    init();
});
</script>
</html>