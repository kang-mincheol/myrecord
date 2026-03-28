var KG_TO_LB   = 2.20462;
var currentUnit = 'kg';
var currentLogId = null;

/* ─────────────────────────────────────────
   진입점
───────────────────────────────────────── */
function initPage(logId) {
    currentLogId = logId;
    loadingOn();

    $.ajax({
        type: 'POST',
        url: '/api/workout_log/get.workout_log_detail.php',
        contentType: 'application/json',
        data: JSON.stringify({ log_id: logId }),
        success: function (res) {
            loadingOff();
            if (res.code === 'SUCCESS') {
                currentUnit = res.data.weight_unit || 'kg';
                renderPage(res.data);
            } else if (res.code === 'FORBIDDEN') {
                myrecordAlert('on', '접근 권한이 없습니다', '알림', 'location.href="/workout_log/list/"');
            } else {
                myrecordAlert('on', res.msg || '데이터를 불러올 수 없습니다', '알림', 'location.href="/workout_log/list/"');
            }
        },
        error: function () {
            loadingOff();
            myrecordAlert('on', '서버 오류가 발생했습니다', '알림', 'location.href="/workout_log/list/"');
        },
    });
}

/* ─────────────────────────────────────────
   전체 페이지 렌더링
───────────────────────────────────────── */
function renderPage(data) {
    var root = document.getElementById('workout_view_root');
    root.innerHTML =
        renderHeader(data) +
        '<div class="workout_view_wrap">' +
            renderUnitToggle(data.weight_unit) +
            renderExercises(data.exercises, data.weight_unit) +
            renderTotalVolume(data.exercises, data.weight_unit) +
            renderMemo(data.memo) +
            renderFooter(data.id) +
        '</div>';
}

/* ── 페이지 헤더 ── */
function renderHeader(data) {
    var date      = formatDate(data.workout_date);
    var titleHtml = data.title
        ? '<p class="page_title">' + escHtml(data.title) + '</p>' +
          '<p class="page_subtitle">' + escHtml(date) + '</p>'
        : '<p class="page_title">' + escHtml(date) + '</p>';
    var durationHtml = data.workout_duration
        ? '<p class="page_subtitle"><i class="fa-regular fa-clock"></i> ' + parseInt(data.workout_duration) + '분 운동</p>'
        : '';
    return '<div class="workout_log_header">' +
               '<div class="header_inner">' +
                   titleHtml +
                   durationHtml +
               '</div>' +
           '</div>';
}

/* ── 단위 토글 ── */
function renderUnitToggle(unit) {
    return '<div class="view_unit_toggle_wrap">' +
               '<span class="view_unit_label"><i class="fa-solid fa-weight-hanging"></i> 무게 단위</span>' +
               '<div class="unit_toggle_wrap">' +
                   '<button type="button" class="unit_toggle_btn ' + (unit === 'kg' ? 'active' : '') + '" data-unit="kg" onclick="toggleUnit(\'kg\', this);">KG</button>' +
                   '<button type="button" class="unit_toggle_btn ' + (unit === 'lb' ? 'active' : '') + '" data-unit="lb" onclick="toggleUnit(\'lb\', this);">LB</button>' +
               '</div>' +
           '</div>';
}

/* ── 종목 카드 목록 ── */
function renderExercises(exercises, unit) {
    if (!exercises || exercises.length === 0) {
        return '<div class="empty_exercise">등록된 종목이 없습니다.</div>';
    }
    return exercises.map(function (ex, idx) {
        return renderExerciseCard(ex, idx, unit);
    }).join('');
}

function renderExerciseCard(ex, idx, unit) {
    var totalVolume = 0;
    var setRows = (ex.sets || []).map(function (set) {
        var weight = parseFloat(set.weight);
        var reps   = parseInt(set.reps);
        var w      = unit === 'lb' ? weight * KG_TO_LB : weight;
        var vol    = w * reps;
        totalVolume += weight * reps; // 항상 kg 기준으로 누적

        return '<tr>' +
            '<td class="set_no_cell">' + parseInt(set.set_no) + 'set</td>' +
            '<td class="weight_cell" data-weight="' + weight + '">' +
                '<span class="weight_val">' + w.toFixed(1) + '</span>' +
                '<span class="td_unit">' + unit + '</span>' +
            '</td>' +
            '<td class="reps_cell">' + reps + '<span class="td_unit">회</span></td>' +
            '<td class="volume_cell" data-weight="' + weight + '" data-reps="' + reps + '">' +
                '<span class="volume_val">' + vol.toFixed(1) + '</span>' +
                '<span class="td_unit">' + unit + '</span>' +
            '</td>' +
        '</tr>';
    }).join('');

    var totalDisplay = unit === 'lb' ? totalVolume * KG_TO_LB : totalVolume;

    return '<div class="exercise_card">' +
        '<div class="exercise_card_title">' +
            '<span class="exercise_order">' + (idx + 1) + '</span>' +
            '<span class="exercise_name">' + escHtml(ex.exercise_name) + '</span>' +
        '</div>' +
        '<table class="set_table">' +
            '<thead><tr><th>세트</th><th>무게</th><th>횟수</th><th>볼륨</th></tr></thead>' +
            '<tbody>' + setRows + '</tbody>' +
            '<tfoot>' +
                '<tr class="total_row" data-total-volume="' + totalVolume + '">' +
                    '<td colspan="3">총 볼륨</td>' +
                    '<td><span class="total_vol_val">' + totalDisplay.toFixed(1) + '</span>' +
                    '<span class="td_unit">' + unit + '</span></td>' +
                '</tr>' +
            '</tfoot>' +
        '</table>' +
    '</div>';
}

/* ── 총 볼륨 카드 ── */
function renderTotalVolume(exercises, unit) {
    if (!exercises || exercises.length === 0) return '';

    var grandTotalKg = 0;
    var rows = exercises.map(function(ex) {
        var exVolKg = 0;
        (ex.sets || []).forEach(function(set) {
            exVolKg += parseFloat(set.weight) * parseInt(set.reps);
        });
        grandTotalKg += exVolKg;
        var exDisplay = unit === 'lb' ? exVolKg * KG_TO_LB : exVolKg;
        return '<div class="total_vol_row">' +
            '<span class="total_vol_ex_name">' + escHtml(ex.exercise_name) + '</span>' +
            '<span class="total_vol_ex_val" data-vol-kg="' + exVolKg + '">' +
                exDisplay.toFixed(1) + '<span class="grand_total_unit"> ' + unit + '</span>' +
            '</span>' +
        '</div>';
    }).join('');

    var grandDisplay = unit === 'lb' ? grandTotalKg * KG_TO_LB : grandTotalKg;

    return '<div class="total_volume_card">' +
        '<div class="total_volume_header">' +
            '<span class="total_volume_title"><i class="fa-solid fa-fire-flame-curved"></i> 오늘의 총 볼륨</span>' +
            '<span class="total_volume_value" data-total-kg="' + grandTotalKg + '">' +
                '<span class="grand_total_val">' + grandDisplay.toFixed(1) + '</span>' +
                '<span class="grand_total_unit"> ' + unit + '</span>' +
            '</span>' +
        '</div>' +
        '<div class="total_vol_breakdown">' + rows + '</div>' +
    '</div>';
}

/* ── 메모 ── */
function renderMemo(memo) {
    if (!memo) return '';
    return '<div class="memo_card">' +
               '<div class="memo_card_title"><i class="fa-regular fa-note-sticky"></i> 메모</div>' +
               '<p class="memo_content">' + escHtml(memo).replace(/\n/g, '<br>') + '</p>' +
           '</div>';
}

/* ── 하단 버튼 ── */
function renderFooter(logId) {
    return '<div class="view_footer">' +
               '<a href="/workout_log/list/" class="list_btn"><i class="fa-solid fa-list"></i> 목록</a>' +
               '<a href="/workout_log/edit/?id=' + logId + '" class="edit_btn"><i class="fa-solid fa-pen"></i> 수정</a>' +
               '<button class="delete_btn" onclick="confirmDelete(' + logId + ')"><i class="fa-solid fa-trash"></i> 삭제</button>' +
           '</div>';
}

/* ─────────────────────────────────────────
   단위 토글
───────────────────────────────────────── */
function toggleUnit(unit, btn) {
    if (currentUnit === unit) return;
    currentUnit = unit;

    document.querySelectorAll('.unit_toggle_btn').forEach(function (b) {
        b.classList.toggle('active', b.dataset.unit === unit);
    });

    document.querySelectorAll('.weight_cell[data-weight]').forEach(function (td) {
        var kg  = parseFloat(td.dataset.weight);
        var val = unit === 'lb' ? kg * KG_TO_LB : kg;
        td.querySelector('.weight_val').textContent = val.toFixed(1);
        td.querySelector('.td_unit').textContent    = unit;
    });

    document.querySelectorAll('.volume_cell[data-weight]').forEach(function (td) {
        var kg   = parseFloat(td.dataset.weight);
        var reps = parseInt(td.dataset.reps);
        var w    = unit === 'lb' ? kg * KG_TO_LB : kg;
        td.querySelector('.volume_val').textContent = (w * reps).toFixed(1);
        td.querySelector('.td_unit').textContent    = unit;
    });

    document.querySelectorAll('.total_row[data-total-volume]').forEach(function (tr) {
        var totalKg = parseFloat(tr.dataset.totalVolume);
        var val     = unit === 'lb' ? totalKg * KG_TO_LB : totalKg;
        tr.querySelector('.total_vol_val').textContent = val.toFixed(1);
        tr.querySelector('.td_unit').textContent       = unit;
    });

    var grandTotalEl = document.querySelector('.total_volume_value[data-total-kg]');
    if (grandTotalEl) {
        var totalKg = parseFloat(grandTotalEl.dataset.totalKg);
        var val     = unit === 'lb' ? totalKg * KG_TO_LB : totalKg;
        grandTotalEl.querySelector('.grand_total_val').textContent = val.toFixed(1);
    }
    document.querySelectorAll('.total_vol_ex_val[data-vol-kg]').forEach(function(el) {
        var kg  = parseFloat(el.dataset.volKg);
        var val = unit === 'lb' ? kg * KG_TO_LB : kg;
        el.childNodes[0].textContent = val.toFixed(1);
    });
    document.querySelectorAll('.grand_total_unit').forEach(function(el) {
        el.textContent = ' ' + unit;
    });
}

/* ─────────────────────────────────────────
   삭제
───────────────────────────────────────── */
function confirmDelete(logId) {
    myrecordConfirm(
        'on',
        '이 득근일지를 삭제하시겠습니까?<br/>삭제 후 복구가 불가능합니다',
        function () { deleteLog(logId); },
        '삭제 확인'
    );
}

function deleteLog(logId) {
    $.ajax({
        url: '/api/workout_log/set.workout_log_delete.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ log_id: logId }),
        success: function (res) {
            if (res.code === 'SUCCESS') {
                location.href = '/workout_log/list/';
            } else {
                myrecordAlert('on', res.msg || '오류가 발생했습니다', '알림', '');
            }
        },
        error: function () {
            myrecordAlert('on', '서버 오류가 발생했습니다', '알림', '');
        },
    });
}

/* ─────────────────────────────────────────
   유틸
───────────────────────────────────────── */
function formatDate(dateStr) {
    var days = ['일', '월', '화', '수', '목', '금', '토'];
    var d    = new Date(dateStr + 'T00:00:00');
    return d.getFullYear() + '년 ' +
           (d.getMonth() + 1) + '월 ' +
           d.getDate() + '일' +
           ' (' + days[d.getDay()] + ')';
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
