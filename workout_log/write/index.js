var exerciseIndex = 0;
var weightUnit = 'kg';
var KG_TO_LB = 2.20462;
var autoSavedLogId = null;
var AUTO_SAVE_INTERVAL = 10 * 60 * 1000; // 10분

/* ── 무게 단위 선택 ── */
function setWeightUnit(unit, btn) {
    if (weightUnit === unit) return;

    // 기존 입력값 환산
    document.querySelectorAll('.set_weight_input').forEach(function(input) {
        var val = parseFloat(input.value);
        if (!isNaN(val) && val > 0) {
            input.value = unit === 'lb'
                ? (val * KG_TO_LB).toFixed(1)
                : (val / KG_TO_LB).toFixed(1);
        }
    });

    weightUnit = unit;
    document.querySelectorAll('.unit_toggle_btn').forEach(function(b) {
        b.classList.toggle('active', b.dataset.unit === unit);
    });
    document.querySelectorAll('.set_weight_unit').forEach(function(span) {
        span.textContent = unit;
    });
}

function init() {
    // 오늘 날짜 기본값
    var today = new Date();
    var yyyy  = today.getFullYear();
    var mm    = String(today.getMonth() + 1).padStart(2, '0');
    var dd    = String(today.getDate()).padStart(2, '0');
    $('#workout_date').val(yyyy + '-' + mm + '-' + dd);

    // 첫 번째 종목 자동 추가
    addExercise();

    // 10분마다 자동 저장 시작
    setInterval(autoSave, AUTO_SAVE_INTERVAL);
}

/* ── 자동 저장 (서버) ── */
function autoSave() {
    var workout_date = $('#workout_date').val().trim();
    if (!workout_date) return; // 날짜 없으면 스킵

    // 유효한 종목만 수집 (이름 있는 것)
    var exercises = [];
    document.querySelectorAll('#exercise_list .exercise_block').forEach(function(block) {
        var exName = block.querySelector('.exercise_name_input').value.trim();
        if (!exName) return;
        var sets = [];
        block.querySelectorAll('.set_row').forEach(function(row) {
            var w = row.querySelector('.set_weight_input').value.trim();
            var r = row.querySelector('.set_reps_input').value.trim();
            if (w !== '' && r !== '') {
                sets.push({ weight: parseFloat(w) || 0, reps: parseInt(r) || 0 });
            }
        });
        if (sets.length > 0) {
            exercises.push({ exercise_name: exName, sets: sets });
        }
    });

    if (exercises.length === 0) return; // 저장할 종목 없으면 스킵

    var payload = {
        workout_date:     workout_date,
        workout_duration: $('#workout_duration').val().trim() !== '' ? parseInt($('#workout_duration').val().trim()) : null,
        memo:             $('#workout_memo').val().trim(),
        weight_unit:      weightUnit,
        exercises:        exercises
    };

    if (autoSavedLogId) {
        payload.log_id = autoSavedLogId;
    }

    $.ajax({
        url: '/api/workout_log/set.workout_log_save.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(res) {
            if (res.code === 'SUCCESS') {
                autoSavedLogId = res.log_id;
                showToast('자동 저장되었습니다', 'success');
            } else {
                showToast('자동 저장에 실패했습니다', 'error');
            }
        },
        error: function() {
            showToast('자동 저장에 실패했습니다', 'error');
        }
    });
}

/* ── 종목 추가 ── */
function addExercise() {
    var tpl   = document.getElementById('exercise_tpl');
    var clone = tpl.content.cloneNode(true);
    var block = clone.querySelector('.exercise_block');
    block.setAttribute('data-index', exerciseIndex);
    document.getElementById('exercise_list').appendChild(clone);
    exerciseIndex++;

    // 첫 번째 세트 자동 추가
    var blocks = document.querySelectorAll('#exercise_list .exercise_block');
    var lastBlock = blocks[blocks.length - 1];
    addSetToBlock(lastBlock.querySelector('.add_set_btn'));
}

/* ── 종목 삭제 ── */
function removeExercise(btn) {
    var block = btn.closest('.exercise_block');
    block.remove();
}

/* ── 세트 추가 (addSet_btn 클릭용) ── */
function addSet(btn) {
    addSetToBlock(btn);
}

function addSetToBlock(addBtn) {
    var block   = addBtn.closest('.exercise_block');
    var setList = block.querySelector('.set_list');

    var tpl   = document.getElementById('set_tpl');
    var clone = tpl.content.cloneNode(true);

    // 현재 선택된 단위 반영
    clone.querySelector('.set_weight_unit').textContent = weightUnit;

    setList.appendChild(clone);

    // 세트 번호 업데이트
    updateSetNumbers(setList);

    // 마지막 세트의 weight input에 포커스
    var rows = setList.querySelectorAll('.set_row');
    var last = rows[rows.length - 1];
    last.querySelector('.set_weight_input').focus();
}

/* ── 세트 삭제 ── */
function removeSet(btn) {
    var row     = btn.closest('.set_row');
    var setList = row.closest('.set_list');
    var rows    = setList.querySelectorAll('.set_row');
    if(rows.length <= 1) {
        myrecordAlert('on', '세트는 최소 1개 이상이어야 합니다', '알림', '');
        return;
    }
    row.remove();
    updateSetNumbers(setList);
}

/* ── 세트 번호 갱신 ── */
function updateSetNumbers(setList) {
    var rows = setList.querySelectorAll('.set_row');
    rows.forEach(function(row, idx) {
        row.querySelector('.set_no_label').textContent = (idx + 1) + 'set';
    });
}

/* ── 메모 글자 수 ── */
function memoCount(el) {
    var len = el.value.length;
    $('#memo_count').text(len);
}

/* ── 중간저장 (페이지 이동 없음) ── */
function intermediateSave(logId) {
    var workout_date = $('#workout_date').val().trim();
    if (!workout_date) {
        myrecordAlert('on', '운동 날짜를 입력해주세요', '알림', '');
        return;
    }

    var exercises = [];
    document.querySelectorAll('#exercise_list .exercise_block').forEach(function(block) {
        var exName = block.querySelector('.exercise_name_input').value.trim();
        if (!exName) return;
        var sets = [];
        block.querySelectorAll('.set_row').forEach(function(row) {
            var w = row.querySelector('.set_weight_input').value.trim();
            var r = row.querySelector('.set_reps_input').value.trim();
            if (w !== '' && r !== '') {
                sets.push({ weight: parseFloat(w) || 0, reps: parseInt(r) || 0 });
            }
        });
        if (sets.length > 0) {
            exercises.push({ exercise_name: exName, sets: sets });
        }
    });

    if (exercises.length === 0) {
        myrecordAlert('on', '저장할 운동 종목을 1개 이상 입력해주세요', '알림', '');
        return;
    }

    var payload = {
        workout_date:     workout_date,
        workout_duration: $('#workout_duration').val().trim() !== '' ? parseInt($('#workout_duration').val().trim()) : null,
        memo:             $('#workout_memo').val().trim(),
        weight_unit:      weightUnit,
        exercises:        exercises
    };

    var resolvedLogId = logId || autoSavedLogId;
    if (resolvedLogId) {
        payload.log_id = resolvedLogId;
    }

    var $btn = $('.temp_save_btn');
    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> 저장 중...');

    $.ajax({
        url: '/api/workout_log/set.workout_log_save.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa-regular fa-floppy-disk"></i> 중간저장');
            if (res.code === 'SUCCESS') {
                if (!logId) autoSavedLogId = res.log_id;
                showToast('중간저장 되었습니다', 'success');
            } else {
                myrecordAlert('on', res.msg || '오류가 발생했습니다', '알림', '');
            }
        },
        error: function() {
            $btn.prop('disabled', false).html('<i class="fa-regular fa-floppy-disk"></i> 중간저장');
            myrecordAlert('on', '서버 오류가 발생했습니다', '알림', '');
        }
    });
}

/* ── 저장 ── */
function saveLog(logId) {
    if (!logId && autoSavedLogId) {
        logId = autoSavedLogId;
    }
    var workout_date = $('#workout_date').val().trim();
    if(!workout_date) {
        myrecordAlert('on', '운동 날짜를 입력해주세요', '알림', '');
        return;
    }

    var durationVal = $('#workout_duration').val().trim();
    var workout_duration = durationVal !== '' ? parseInt(durationVal) : null;
    if(workout_duration !== null && (isNaN(workout_duration) || workout_duration < 1 || workout_duration > 1440)) {
        myrecordAlert('on', '운동 시간은 1분 ~ 1440분 사이로 입력해주세요', '알림', '');
        return;
    }

    var memo = $('#workout_memo').val().trim();

    // 종목 수집
    var exercises = [];
    var valid = true;
    var exBlocks = document.querySelectorAll('#exercise_list .exercise_block');

    if(exBlocks.length === 0) {
        myrecordAlert('on', '운동 종목을 1개 이상 입력해주세요', '알림', '');
        return;
    }

    exBlocks.forEach(function(block, bIdx) {
        if(!valid) return;
        var exName = block.querySelector('.exercise_name_input').value.trim();
        if(!exName) {
            myrecordAlert('on', (bIdx + 1) + '번째 종목명을 입력해주세요', '알림', '');
            valid = false;
            return;
        }

        var sets = [];
        var setRows = block.querySelectorAll('.set_row');
        setRows.forEach(function(row, sIdx) {
            if(!valid) return;
            var w = row.querySelector('.set_weight_input').value.trim();
            var r = row.querySelector('.set_reps_input').value.trim();
            if(w === '' || r === '') {
                myrecordAlert('on', exName + ' ' + (sIdx + 1) + '세트의 무게와 횟수를 입력해주세요', '알림', '');
                valid = false;
                return;
            }
            sets.push({ weight: parseFloat(w) || 0, reps: parseInt(r) || 0 });
        });

        if(valid) {
            exercises.push({ exercise_name: exName, sets: sets });
        }
    });

    if(!valid) return;

    var payload = {
        workout_date:     workout_date,
        workout_duration: workout_duration,
        memo:             memo,
        weight_unit:      weightUnit,
        exercises:        exercises
    };

    if(logId) {
        payload.log_id = logId;
    }

    var $saveBtn = $('.save_btn');
    $saveBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> 저장 중...');

    $.ajax({
        url: '/api/workout_log/set.workout_log_save.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(res) {
            if(res.code === 'SUCCESS') {
                location.href = '/workout_log/view/?id=' + res.log_id;
            } else {
                myrecordAlert('on', res.msg || '오류가 발생했습니다', '알림', '');
                $saveBtn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> 저장하기');
            }
        },
        error: function() {
            myrecordAlert('on', '서버 오류가 발생했습니다', '알림', '');
            $saveBtn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> 저장하기');
        }
    });
}
