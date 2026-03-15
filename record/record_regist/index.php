<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/common/record_pages.css');
echo css_load('/component/input/input.css');
echo css_load('/record/record_regist/index.css');

if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}
?>


<div class="record_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">기록 등록</p>
        <p class="page_sub_text">종목과 무게를 선택하고 인증 파일을 첨부해 주세요</p>
    </div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/component/sub_menu/record_sub_menu/record_sub_menu.php');
?>


<div id="notice_popup_wrap" class="on">
    <div class="popup_box">
        <div class="popup_header">
            <i class="fa-solid fa-triangle-exclamation"></i>
            알림
        </div>
        <div class="popup_body">
            Record 등록 후 삭제는 가능하나 수정이 불가합니다.<br/>
            Record 종목 / 무게 / 첨부파일을 다시 한번 확인 후 등록해 주세요.
        </div>
        <button class="access_btn" onclick="noticePopupOff();">확인</button>
    </div>
</div>
<!-- notice_popup_wrap -->


<div class="edit_wrap">

    <div class="form_card">

        <div class="record_option_wrap">

            <div class="myrecord_input_wrap record_type">
                <div class="label_box">
                    <label class="wrap_label">종목 선택</label>
                </div>
                <div class="myrecord_select_wrap">
                    <button id="record_type" class="select_remote_btn" value="none" onclick="selectListRemote(this);">선택하세요</button>
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

            <div class="myrecord_input_wrap weight_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="record_weight">무게 (kg)</label>
                </div>
                <div class="form_value_box">
                    <input id="record_weight" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number" placeholder="kg 단위로 입력해 주세요"/>
                </div>
            </div>

        </div>
        <!-- record_option_wrap -->


        <div class="myrecord_input_wrap file_wrap">
            <div class="label_box">
                <label class="wrap_label">인증 파일 첨부</label>
            </div>
            <div class="file_add_wrap">
                <div class="file_add_wrap_header">
                    <button class="add_btn" onclick="fileAdd();"><i class="fa-solid fa-plus"></i> 파일 추가</button>
                    <p class="file_guide_text">동영상 · 이미지 파일, 개당 최대 100MB / 총 8MB</p>
                </div>
                <div class="file_row_box"></div>
            </div>
        </div>
        <!-- file_wrap -->


        <div class="footer_btn_wrap">
            <button class="list_btn" onclick="prev();"><i class="fa-solid fa-angle-left"></i> 이전</button>
            <button class="update_btn" onclick="setRecordData();">등록하기</button>
        </div>

    </div>
    <!-- form_card -->

</div>
<!-- edit_wrap -->


<?php
echo script_load("/component/input/input.js");
echo script_load('/record/record_regist/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
