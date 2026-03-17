var currentUnit = 'kg';
var KG_TO_LB = 2.20462;

function setUnit(unit, btn) {
    if (currentUnit === unit) return;
    currentUnit = unit;

    document.querySelectorAll('.unit_toggle_btn').forEach(function(b) {
        b.classList.toggle('active', b.dataset.unit === unit);
    });

    var display = unit === 'kg' ? 'KG' : 'LB';
    document.querySelector('.unit_display').textContent = display;
    document.querySelector('.result_unit').textContent = display;

    // 결과가 표시된 상태면 재계산
    if ($('#calculator_wrap .result_box').hasClass('on')) {
        rmCalc();
    }
}

function rmCalc() {
    var weight = parseFloat($('#rm_weight').val());
    var reps   = parseInt($('#rm_reps').val());

    if (isNaN(weight) || weight <= 0) {
        myrecordAlert('on', '무게를 입력해주세요', '알림', '');
        return;
    }
    if (isNaN(reps) || reps < 1 || reps > 30) {
        myrecordAlert('on', '횟수는 1 ~ 30 사이로 입력해주세요', '알림', '');
        return;
    }

    // Epley 공식: 1RM = weight × (1 + reps / 30)
    var oneRM = weight * (1 + reps / 30);

    updateResult(oneRM);
    $('#calculator_wrap .result_box').addClass('on');
}

function liveCalc() {
    if (!$('#calculator_wrap .result_box').hasClass('on')) return;
    var weight = parseFloat($('#rm_weight').val());
    var reps   = parseInt($('#rm_reps').val());
    if (isNaN(weight) || weight <= 0 || isNaN(reps) || reps < 1 || reps > 30) return;

    var oneRM = weight * (1 + reps / 30);
    updateResult(oneRM);
}

function updateResult(oneRM) {
    // 단위에 맞춰 반올림 (0.5 단위)
    var rounded = Math.round(oneRM * 2) / 2;
    var display = rounded % 1 === 0 ? rounded.toFixed(0) : rounded.toFixed(1);

    $('#calculator_wrap .result_box .result_value_box .value').text(display);

    // 훈련 중량 테이블 업데이트
    $('#calculator_wrap .result_box .percent_row[data-pct]').each(function() {
        var pct = parseInt($(this).data('pct'));
        var pctWeight = oneRM * pct / 100;
        var pctRounded = Math.round(pctWeight * 2) / 2;
        var pctDisplay = pctRounded % 1 === 0 ? pctRounded.toFixed(0) : pctRounded.toFixed(1);
        $(this).find('.pct_weight').text(pctDisplay + ' ' + currentUnit.toUpperCase());
    });
}

function rmReset() {
    $('#rm_weight').val('');
    $('#rm_reps').val('');
    $('#calculator_wrap .result_box').removeClass('on');
}
