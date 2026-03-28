var exerciseIndex = 0;
var weightUnit = 'kg';
var KG_TO_LB = 2.20462;
var autoSavedLogId = null;
var AUTO_SAVE_INTERVAL = 10 * 60 * 1000; // 10분

/* ── 로컬 임시저장 ── */
var draftKey = 'workout_log_write_draft';
var draftSaveTimer = null;

function scheduleDraftSave() {
    clearTimeout(draftSaveTimer);
    draftSaveTimer = setTimeout(saveDraftToLocal, 500);
}

function saveDraftToLocal() {
    var exercises = [];
    document.querySelectorAll('#exercise_list .exercise_block').forEach(function(block) {
        var sets = [];
        block.querySelectorAll('.set_row').forEach(function(row) {
            sets.push({
                weight: row.querySelector('.set_weight_input').value,
                reps:   row.querySelector('.set_reps_input').value
            });
        });
        exercises.push({
            exercise_name: block.querySelector('.exercise_name_input').value,
            sets: sets
        });
    });
    var data = {
        title:            $('#workout_title').val(),
        workout_date:     $('#workout_date').val(),
        workout_duration: $('#workout_duration').val(),
        memo:             $('#workout_memo').val(),
        weight_unit:      weightUnit,
        exercises:        exercises,
        saved_at:         new Date().toISOString()
    };
    try { localStorage.setItem(draftKey, JSON.stringify(data)); } catch(e) {}
}

function checkAndRestoreDraft() {
    try {
        var raw = localStorage.getItem(draftKey);
        if (!raw) return;
        var draft = JSON.parse(raw);
        if (!draft || !draft.saved_at) return;
        var timeStr = new Date(draft.saved_at).toLocaleString('ko-KR');
        showRestoreBanner(timeStr);
    } catch(e) {
        localStorage.removeItem(draftKey);
    }
}

function showRestoreBanner(timeStr) {
    var banner = document.createElement('div');
    banner.id = 'draft_restore_banner';
    banner.innerHTML =
        '<span class="draft_banner_text"><i class="fa-solid fa-clock-rotate-left"></i> ' + timeStr + '에 저장된 임시 데이터가 있습니다</span>' +
        '<div class="draft_banner_btns">' +
        '<button class="draft_restore_btn" onclick="restoreDraft()">불러오기</button>' +
        '<button class="draft_dismiss_btn" onclick="dismissDraft()">삭제</button>' +
        '</div>';
    var wrap = document.querySelector('.workout_write_wrap');
    wrap.insertBefore(banner, wrap.firstChild);
}

function restoreDraft() {
    try {
        var raw = localStorage.getItem(draftKey);
        if (!raw) return;
        var draft = JSON.parse(raw);

        if (draft.title)            $('#workout_title').val(draft.title);
        if (draft.workout_date)     $('#workout_date').val(draft.workout_date);
        if (draft.workout_duration) $('#workout_duration').val(draft.workout_duration);
        if (draft.memo) {
            $('#workout_memo').val(draft.memo);
            memoCount(document.getElementById('workout_memo'));
        }
        if (draft.weight_unit) setWeightUnit(draft.weight_unit, null);

        if (draft.exercises && draft.exercises.length > 0) {
            document.getElementById('exercise_list').innerHTML = '';
            exerciseIndex = 0;
            draft.exercises.forEach(function(ex) {
                addExercise();
                var blocks = document.querySelectorAll('#exercise_list .exercise_block');
                var lastBlock = blocks[blocks.length - 1];
                lastBlock.querySelector('.exercise_name_input').value = ex.exercise_name;

                var setList = lastBlock.querySelector('.set_list');
                setList.innerHTML = '';
                (ex.sets || []).forEach(function(set) {
                    addSetToBlock(lastBlock.querySelector('.add_set_btn'));
                    var rows = setList.querySelectorAll('.set_row');
                    var lastRow = rows[rows.length - 1];
                    lastRow.querySelector('.set_weight_input').value = set.weight;
                    lastRow.querySelector('.set_reps_input').value   = set.reps;
                    lastRow.querySelector('.set_weight_unit').textContent = draft.weight_unit || 'kg';
                });
            });
        }
    } catch(e) {}

    dismissDraft();
    showToast('임시 데이터를 불러왔습니다', 'success');
}

function dismissDraft() {
    clearLocalDraft();
    var banner = document.getElementById('draft_restore_banner');
    if (banner) banner.remove();
}

function clearLocalDraft() {
    try { localStorage.removeItem(draftKey); } catch(e) {}
}

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
    setInterval(function() { saveProgress(false); }, AUTO_SAVE_INTERVAL);

    // 입력 시 로컬 임시저장 (500ms debounce)
    $(document).on('input change', '#workout_title, #workout_date, #workout_duration, #workout_memo, .exercise_name_input, .set_weight_input, .set_reps_input', scheduleDraftSave);

    // 로컬 임시저장 복구 확인
    checkAndRestoreDraft();
}

/* ── 통합 저장 (자동저장 + 중간저장) ── */
function saveProgress(isManual) {
    var workout_date = $('#workout_date').val().trim();

    if (isManual && !workout_date) {
        myrecordAlert('on', '운동 날짜를 입력해주세요', '알림', '');
        return;
    }
    if (!isManual && !workout_date) return;

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

    if (isManual && exercises.length === 0) {
        myrecordAlert('on', '저장할 운동 종목을 1개 이상 입력해주세요', '알림', '');
        return;
    }
    if (!isManual && exercises.length === 0) return;

    var payload = {
        title:            $('#workout_title').val().trim(),
        workout_date:     workout_date,
        workout_duration: $('#workout_duration').val().trim() !== '' ? parseInt($('#workout_duration').val().trim()) : null,
        memo:             $('#workout_memo').val().trim(),
        weight_unit:      weightUnit,
        exercises:        exercises
    };

    var saveUrl    = autoSavedLogId ? '/api/v1/workout-logs/' + autoSavedLogId : '/api/v1/workout-logs';
    var saveMethod = autoSavedLogId ? 'PUT' : 'POST';

    if (isManual) {
        var $btn = $('.temp_save_btn');
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> 저장 중...');
    }

    $.ajax({
        url: saveUrl,
        type: saveMethod,
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(res) {
            if (isManual) {
                $('.temp_save_btn').prop('disabled', false).html('<i class="fa-regular fa-floppy-disk"></i> 중간저장');
            }
            if (res.code === 'SUCCESS') {
                autoSavedLogId = res.log_id;
                clearLocalDraft();
                showToast(isManual ? '중간저장 되었습니다' : '자동 저장되었습니다', 'success');
            } else {
                if (isManual) {
                    myrecordAlert('on', res.msg || '오류가 발생했습니다', '알림', '');
                }
            }
        },
        error: function() {
            if (isManual) {
                $('.temp_save_btn').prop('disabled', false).html('<i class="fa-regular fa-floppy-disk"></i> 중간저장');
                myrecordAlert('on', '서버 오류가 발생했습니다', '알림', '');
            }
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

/* ── 저장 ── */
function saveLog(logId) {
    if (!logId && autoSavedLogId) {
        logId = autoSavedLogId;
    }
    var title = $('#workout_title').val().trim();
    if(!title) {
        myrecordAlert('on', '제목을 입력해주세요', '알림', '');
        return;
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
        title:            $('#workout_title').val().trim(),
        workout_date:     workout_date,
        workout_duration: workout_duration,
        memo:             memo,
        weight_unit:      weightUnit,
        exercises:        exercises
    };

    var saveUrl    = logId ? '/api/v1/workout-logs/' + logId : '/api/v1/workout-logs';
    var saveMethod = logId ? 'PUT' : 'POST';

    var $saveBtn = $('.save_btn');
    $saveBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> 저장 중...');

    $.ajax({
        url: saveUrl,
        type: saveMethod,
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(res) {
            if(res.code === 'SUCCESS') {
                clearLocalDraft();
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
