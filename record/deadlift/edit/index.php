<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드


echo css_load("/component/input/input.css");
?>


<div id="page_title">마이레코드 ＞ DeadLift</div>

<?
include_once($_SERVER['DOCUMENT_ROOT'].'/component/sub_menu/record_sub_menu/record_sub_menu.php');
echo css_load('/record/deadlift/edit/index.css');
?>


<div class="edit_wrap">

    <div class="edit_title">Record 등록</div>

    <div class="record_option_wrap">

        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label">Record 선택</label>
            </div>
            <div class="myrecord_select_wrap">
                <button class="select_remote_btn" value="none" onclick="selectListRemote(this);">선택하세요</button>
                <div class="select_list_wrap">
                    <button class="select_list_btn" value="none" onclick="optionClick(this);">선택하세요</button>
                    <button class="select_list_btn" value="1" onclick="optionClick(this);">Squat</button>
                    <button class="select_list_btn" value="2" onclick="optionClick(this);">BenchPress</button>
                    <button class="select_list_btn" value="3" onclick="optionClick(this);">DeadLift</button>
                </div>
                <div class="mobile_select_wrap">
                    <select class="mobile_select" onchange="mobileOptionClick(this);">
                        <option value="none">선택하세요</option>
                        <option value="1">Squat</option>
                        <option value="2">BenchPress</option>
                        <option value="3">DeadLift</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="record_weight">Record 무게(kg)</label>
            </div>
            <div class="form_value_box">
                <input id="record_weight" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text" placeholder="kg 단위로 입력해주세요"/>
            </div>
        </div>

    </div>
    <!-- record_option_wrap -->
    
    

    <div class="myrecord_input_wrap file_wrap">
        <div class="label_box">
            <label class="wrap_label">파일 첨부</label>
        </div>
        <div class="file_add_wrap">
            <div class="file_add_wrap_header">
                <button class="add_btn" onclick="fileAdd();">추가</button>
            </div>

            <div class="file_row_box">
<!--
                <div class="file_row">
                    <input name="file_1" type="file"/>
                    <div class="file_name_box">파일없음</div>
                    <div class="file_row_remote_box">
                        <button class="file_select_btn" onclick="">선택</button>
                        <button class="file_delete_btn" onclick="">삭제</button>
                    </div>
                </div>
-->
            </div>
            <!-- file_row_box -->
        </div>
    </div>
    <!-- file_wrap -->

</div>
<!-- edit_wrap -->



<?
echo script_load("/component/input/input.js");
echo script_load('/record/deadlift/edit/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>