<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

echo css_load('/workout_log/write/index.css');

if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}
?>

<div class="workout_log_header">
    <div class="header_inner">
        <p class="page_title">득근일지 작성</p>
        <p class="page_subtitle">오늘의 운동 기록을 남겨보세요</p>
    </div>
</div>

<div class="workout_write_wrap">

    <!-- 날짜 / 운동시간 -->
    <div class="form_card">
        <div class="form_section_title">
            <i class="fa-regular fa-calendar"></i>
            기본 정보
        </div>

        <div class="form_row">
            <label class="form_label" for="workout_date">운동 날짜 <span class="required_mark">*</span></label>
            <input type="date" id="workout_date" class="form_input" />
        </div>

        <div class="form_row">
            <label class="form_label" for="workout_duration">운동 시간 <span class="optional_tag">선택</span></label>
            <div class="input_with_unit">
                <input type="number" id="workout_duration" class="form_input" placeholder="운동한 시간을 입력해주세요" min="1" max="1440" />
                <span class="unit_label">분</span>
            </div>
        </div>

        <div class="form_row">
            <label class="form_label">무게 단위</label>
            <div class="unit_toggle_wrap">
                <button type="button" class="unit_toggle_btn active" data-unit="kg" onclick="setWeightUnit('kg', this);">KG</button>
                <button type="button" class="unit_toggle_btn" data-unit="lb" onclick="setWeightUnit('lb', this);">LB</button>
            </div>
        </div>
    </div>
    <!-- form_card -->


    <!-- 운동 종목 -->
    <div class="form_card" id="exercise_card">
        <div class="form_section_title">
            <i class="fa-solid fa-dumbbell"></i>
            운동 종목
        </div>

        <div id="exercise_list">
            <!-- 종목 블록이 JS로 추가됩니다 -->
        </div>

        <button class="add_exercise_btn" onclick="addExercise();">
            <i class="fa-solid fa-plus"></i> 종목 추가
        </button>
    </div>
    <!-- exercise_card -->


    <!-- 메모 -->
    <div class="form_card">
        <div class="form_section_title">
            <i class="fa-regular fa-note-sticky"></i>
            메모 <span class="optional_tag">선택</span>
        </div>
        <textarea id="workout_memo" class="form_textarea" placeholder="오늘 운동 전체에 대한 메모를 자유롭게 적어주세요 (최대 500자)" maxlength="500" oninput="memoCount(this);"></textarea>
        <div class="memo_count_wrap"><span id="memo_count">0</span> / 500</div>
    </div>


    <!-- 버튼 -->
    <div class="form_footer">
        <button class="cancel_btn" onclick="history.back();"><i class="fa-solid fa-angle-left"></i> 취소</button>
        <button class="temp_save_btn" onclick="intermediateSave();"><i class="fa-regular fa-floppy-disk"></i> 중간저장</button>
        <button class="save_btn" onclick="saveLog();"><i class="fa-solid fa-check"></i> 저장하기</button>
    </div>

</div>
<!-- workout_write_wrap -->


<!-- 종목 블록 템플릿 (hidden) -->
<template id="exercise_tpl">
    <div class="exercise_block" data-index="">
        <div class="exercise_block_header">
            <div class="exercise_name_wrap">
                <input type="text" class="exercise_name_input" placeholder="운동 종목명 (예: 스쿼트, 벤치프레스)" maxlength="100" />
            </div>
            <button class="exercise_delete_btn" onclick="removeExercise(this);" title="종목 삭제">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="set_list">
            <!-- 세트 행이 JS로 추가됩니다 -->
        </div>

        <button class="add_set_btn" onclick="addSet(this);">
            <i class="fa-solid fa-plus"></i> 세트 추가
        </button>
    </div>
</template>

<!-- 세트 행 템플릿 (hidden) -->
<template id="set_tpl">
    <div class="set_row">
        <span class="set_no_label"></span>
        <div class="set_input_group">
            <div class="set_input_wrap">
                <input type="number" class="set_weight_input" placeholder="0" min="0" max="9999" step="0.5" />
                <span class="set_unit set_weight_unit">kg</span>
            </div>
            <span class="set_x">×</span>
            <div class="set_input_wrap">
                <input type="number" class="set_reps_input" placeholder="0" min="0" max="9999" />
                <span class="set_unit">회</span>
            </div>
        </div>
        <button class="set_delete_btn" onclick="removeSet(this);" title="세트 삭제">
            <i class="fa-solid fa-minus"></i>
        </button>
    </div>
</template>


<?php echo script_load('/workout_log/write/index.js'); ?>
<script>
$(function () {
    init();
});
</script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
