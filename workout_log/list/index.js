var currentPage = 1;

function init() {
    loadList(1);
}

function loadList(page) {
    currentPage = page;

    $('#log_list_box').html('<div class="list_loading"><i class="fa-solid fa-spinner fa-spin"></i></div>');
    $('#pagination_wrap').html('');

    $.ajax({
        url: '/api/workout_log/get.workout_log_list.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ page: page }),
        success: function(res) {
            if(res.code === 'SUCCESS') {
                renderList(res);
                renderPagination(res.total_count, res.page, res.page_size);
                $('#total_count').text(res.total_count);
            } else if(res.code === 'EMPTY') {
                $('#total_count').text('0');
                renderEmpty();
            } else {
                myrecordAlert('on', res.msg || '오류가 발생했습니다', '알림', '');
                $('#log_list_box').html('');
            }
        },
        error: function() {
            myrecordAlert('on', '서버 오류가 발생했습니다', '알림', '');
            $('#log_list_box').html('');
        }
    });
}

function renderList(res) {
    var html = '';
    $.each(res.data, function(i, row) {
        var dateStr    = formatDate(row.workout_date);
        var durationHtml = row.workout_duration
            ? '<span class="log_duration"><i class="fa-regular fa-clock"></i> ' + row.workout_duration + '분</span>'
            : '';
        var summary  = row.exercise_summary || '';
        var memoHtml = row.memo ? '<span class="log_memo_preview">' + escHtml(row.memo) + '</span>' : '<span></span>';

        html += '<a class="log_card" href="/workout_log/view/?id=' + row.id + '">';
        html += '  <div class="log_card_head">';
        html += '    <span class="log_date">' + dateStr + '</span>';
        html += '    ' + durationHtml;
        html += '  </div>';
        html += '  <div class="log_title">' + escHtml(row.title) + '</div>';
        if (summary) {
            html += '  <div class="log_exercise_summary">' + escHtml(summary) + '</div>';
        }
        html += '  <div class="log_card_foot">';
        html += '    <span class="log_exercise_count"><i class="fa-solid fa-dumbbell"></i>' + row.exercise_count + '개 종목</span>';
        html += '    ' + memoHtml;
        html += '  </div>';
        html += '</a>';
    });
    $('#log_list_box').html(html);
}

function renderEmpty() {
    var html = '<div class="list_empty">';
    html += '  <i class="fa-solid fa-dumbbell"></i>';
    html += '  <p>아직 기록된 득근일지가 없어요</p>';
    html += '  <a href="/workout_log/write/" class="empty_write_btn"><i class="fa-solid fa-plus"></i> 첫 기록 남기기</a>';
    html += '</div>';
    $('#log_list_box').html(html);
}

function renderPagination(totalCount, page, pageSize) {
    if(totalCount <= pageSize) return;

    var totalPage  = Math.ceil(totalCount / pageSize);
    var blockSize  = 5;
    var blockStart = Math.floor((page - 1) / blockSize) * blockSize + 1;
    var blockEnd   = Math.min(blockStart + blockSize - 1, totalPage);

    var html = '';

    if(page > 1) {
        html += '<button class="page_btn" onclick="loadList(' + (page - 1) + ')"><i class="fa-solid fa-angle-left"></i></button>';
    }

    for(var i = blockStart; i <= blockEnd; i++) {
        var activeClass = i === page ? ' active' : '';
        html += '<button class="page_btn' + activeClass + '" onclick="loadList(' + i + ')">' + i + '</button>';
    }

    if(page < totalPage) {
        html += '<button class="page_btn" onclick="loadList(' + (page + 1) + ')"><i class="fa-solid fa-angle-right"></i></button>';
    }

    $('#pagination_wrap').html(html);
}

function formatDate(dateStr) {
    if(!dateStr) return '';
    var d = new Date(dateStr);
    var days = ['일', '월', '화', '수', '목', '금', '토'];
    var y = d.getFullYear();
    var m = d.getMonth() + 1;
    var day = d.getDate();
    var dow = days[d.getDay()];
    return y + '년 ' + m + '월 ' + day + '일 (' + dow + ')';
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
