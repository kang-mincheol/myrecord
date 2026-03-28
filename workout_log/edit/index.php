<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

echo css_load('/workout_log/write/index.css');

if (!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}

$log_id = (int)preg_replace("/[^0-9]+/u", "", $_GET['id'] ?? '');
if (!$log_id) {
    echo '<script>myrecordAlert(\'on\', \'잘못된 접근입니다\', \'알림\', \'location.href="/workout_log/list/"\');</script>';
    exit;
}
?>

<div class="workout_log_header">
    <div class="header_inner">
        <p class="page_title">득근일지 수정</p>
        <p class="page_subtitle">기록을 수정해주세요</p>
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
            <label class="form_label" for="workout_title">제목 <span class="required_mark">*</span></label>
            <input type="text" id="workout_title" class="form_input" placeholder="예) 등, 가슴, 어깨, 하체, 전신 등" maxlength="100" />
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
                <button type="button" class="unit_toggle_btn" data-unit="kg" onclick="setWeightUnit('kg', this);">KG</button>
                <button type="button" class="unit_toggle_btn" data-unit="lb" onclick="setWeightUnit('lb', this);">LB</button>
            </div>
        </div>
    </div>


    <!-- 운동 종목 -->
    <div class="form_card" id="exercise_card">
        <div class="form_section_title">
            <i class="fa-solid fa-dumbbell"></i>
            운동 종목
        </div>

        <div id="exercise_list"></div>

        <button class="add_exercise_btn" onclick="addExercise();">
            <i class="fa-solid fa-plus"></i> 종목 추가
        </button>
    </div>


    <!-- 메모 -->
    <div class="form_card">
        <div class="form_section_title">
            <i class="fa-regular fa-note-sticky"></i>
            메모 <span class="optional_tag">선택</span>
        </div>
        <textarea id="workout_memo" class="form_textarea"
                  placeholder="오늘 운동 전체에 대한 메모를 자유롭게 적어주세요 (최대 500자)"
                  maxlength="500"
                  oninput="memoCount(this);"></textarea>
        <div class="memo_count_wrap"><span id="memo_count">0</span> / 500</div>
    </div>


    <!-- 버튼 -->
    <div class="form_footer">
        <button class="cancel_btn" onclick="history.back();"><i class="fa-solid fa-angle-left"></i> 취소</button>
        <button class="temp_save_btn" onclick="saveProgress(true);"><i class="fa-regular fa-floppy-disk"></i> 중간저장</button>
        <button class="save_btn" id="save_btn" onclick="saveLog(autoSavedLogId);" disabled><i class="fa-solid fa-check"></i> 수정하기</button>
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
        <div class="set_list"></div>
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
var exerciseIndex    = 0;
var weightUnit       = 'kg';
var autoSavedLogId   = <?= $log_id ?>;
var draftKey         = 'workout_log_edit_draft_<?= $log_id ?>';

// ── 페이지 로드 시 API에서 데이터 불러와 폼 채우기
fetch('/api/v1/workout-logs/' + autoSavedLogId)
    .then(function (r) { return r.json(); })
    .then(function (res) {
        if (res.code === 'FORBIDDEN' || res.code === 'NOT_FOUND') {
            myrecordAlert('on', '접근 권한이 없습니다', '알림', 'location.href="/workout_log/list/"');
            return;
        }
        if (res.code !== 'SUCCESS') {
            myrecordAlert('on', res.msg || '데이터를 불러올 수 없습니다', '알림', 'location.href="/workout_log/list/"');
            return;
        }
        populateForm(res.data);
        document.getElementById('save_btn').disabled = false;
    })
    .catch(function () {
        myrecordAlert('on', '서버 오류가 발생했습니다', '알림', 'location.href="/workout_log/list/"');
    });

function populateForm(data) {
    document.getElementById('workout_title').value    = data.title || '';
    document.getElementById('workout_date').value     = data.workout_date || '';
    document.getElementById('workout_duration').value = data.workout_duration || '';
    document.getElementById('workout_memo').value     = data.memo || '';
    memoCount(document.getElementById('workout_memo'));

    // 무게 단위 설정
    weightUnit = data.weight_unit || 'kg';
    document.querySelectorAll('.unit_toggle_btn').forEach(function (btn) {
        btn.classList.toggle('active', btn.dataset.unit === weightUnit);
    });

    // 운동 종목 렌더링 (템플릿 직접 사용)
    var $list = document.getElementById('exercise_list');
    $list.innerHTML = '';
    exerciseIndex = 0;

    (data.exercises || []).forEach(function (ex) {
        var exTpl   = document.getElementById('exercise_tpl').content.cloneNode(true);
        var $block  = exTpl.querySelector('.exercise_block');
        $block.dataset.index = exerciseIndex++;
        $block.querySelector('.exercise_name_input').value = ex.exercise_name || '';

        var $setList = $block.querySelector('.set_list');
        $setList.innerHTML = '';

        (ex.sets || []).forEach(function (set, sIdx) {
            var setTpl = document.getElementById('set_tpl').content.cloneNode(true);
            var $row   = setTpl.querySelector('.set_row');
            $row.querySelector('.set_no_label').textContent       = (sIdx + 1) + 'set';
            $row.querySelector('.set_weight_input').value          = set.weight || '';
            $row.querySelector('.set_reps_input').value            = set.reps   || '';
            $row.querySelector('.set_weight_unit').textContent     = weightUnit;
            $setList.appendChild($row);
        });

        $list.appendChild($block);
    });
}

$(function () {
    // 수정 페이지: 데이터 로드 후 타이머만 시작
    setInterval(function () { saveProgress(false); }, AUTO_SAVE_INTERVAL);

    // 입력 시 로컬 임시저장 (500ms debounce)
    $(document).on('input change', '#workout_title, #workout_date, #workout_duration, #workout_memo, .exercise_name_input, .set_weight_input, .set_reps_input', scheduleDraftSave);

    // 로컬 임시저장 복구 확인
    checkAndRestoreDraft();
});
</script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
