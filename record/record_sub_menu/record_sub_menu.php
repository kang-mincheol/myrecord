<?
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

echo css_load('/record/record_sub_menu/record_sub_menu.css');
?>

<div id="record_sub_menu_container">
    <a class="record_sub_menu_btn landing" href="/record/landing/">마이레코드</a>
    <a class="record_sub_menu_btn squat" href="/record/squat/list">Squat</a>
    <a class="record_sub_menu_btn benchpress" href="/record/benchpress/list">BenchPress</a>
    <a class="record_sub_menu_btn deadlift" href="/record/deadlift/list">DeadLift</a>
    <a class="record_sub_menu_btn my_record" href="/record/my_record/">내 기록</a>
</div>

<?
echo script_load('/record/record_sub_menu/record_sub_menu.js');
?>
<script>
$(function() {
    recordSubMenuCheck();
});
</script>