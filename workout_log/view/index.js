var currentUnit = 'kg';
var KG_TO_LB = 2.20462;

function toggleUnit(unit, btn) {
    if (currentUnit === unit) return;
    currentUnit = unit;

    document.querySelectorAll('.unit_toggle_btn').forEach(function(b) {
        b.classList.toggle('active', b.dataset.unit === unit);
    });

    // 무게 셀 환산 (DB 저장값은 항상 data-weight에 kg 기준)
    document.querySelectorAll('.weight_cell[data-weight]').forEach(function(td) {
        var kg  = parseFloat(td.dataset.weight);
        var val = unit === 'lb' ? kg * KG_TO_LB : kg;
        td.querySelector('.weight_val').textContent = val.toFixed(1);
        td.querySelector('.td_unit').textContent = unit;
    });

    // 볼륨 셀 환산
    document.querySelectorAll('.volume_cell[data-weight]').forEach(function(td) {
        var kg   = parseFloat(td.dataset.weight);
        var reps = parseInt(td.dataset.reps);
        var w    = unit === 'lb' ? kg * KG_TO_LB : kg;
        td.querySelector('.volume_val').textContent = (w * reps).toFixed(1);
        td.querySelector('.td_unit').textContent = unit;
    });

    // 총 볼륨 행 환산
    document.querySelectorAll('.total_row[data-total-volume]').forEach(function(tr) {
        var totalKg = parseFloat(tr.dataset.totalVolume);
        var val = unit === 'lb' ? totalKg * KG_TO_LB : totalKg;
        tr.querySelector('.total_vol_val').textContent = val.toFixed(1);
        tr.querySelector('.td_unit').textContent = unit;
    });
}

function confirmDelete(logId) {
  myrecordConfirm(
    "on",
    "이 득근일지를 삭제하시겠습니까?<br/>삭제 후 복구가 불가능합니다",
    () => {
      deleteLog(logId);
    },
    "삭제 확인",
  );
}

function deleteLog(logId) {
  $.ajax({
    url: "/api/workout_log/set.workout_log_delete.php",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ log_id: logId }),
    success: function (res) {
      if (res.code === "SUCCESS") {
        location.href = "/workout_log/list/";
      } else {
        myrecordAlert("on", res.msg || "오류가 발생했습니다", "알림", "");
      }
    },
    error: function () {
      myrecordAlert("on", "서버 오류가 발생했습니다", "알림", "");
    },
  });
}
