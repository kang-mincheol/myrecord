var _calYear  = 0;
var _calMonth = 0;

function initCalendar() {
    var sp = new URLSearchParams(location.search);
    _calYear  = parseInt(sp.get('year')  || new Date().getFullYear());
    _calMonth = parseInt(sp.get('month') || (new Date().getMonth() + 1));

    // 범위 보정
    if (_calMonth < 1)  { _calMonth = 12; _calYear--; }
    if (_calMonth > 12) { _calMonth = 1;  _calYear++; }

    buildCalendarGrid(_calYear, _calMonth);
    loadCalendar(_calYear, _calMonth);
}

function navMonth(delta) {
    _calMonth += delta;
    if (_calMonth < 1)  { _calMonth = 12; _calYear--; }
    if (_calMonth > 12) { _calMonth = 1;  _calYear++; }

    history.replaceState(null, '', '?year=' + _calYear + '&month=' + _calMonth);
    buildCalendarGrid(_calYear, _calMonth);
    loadCalendar(_calYear, _calMonth);
}

function buildCalendarGrid(year, month) {
    // 타이틀 업데이트
    document.getElementById('nav_title').textContent = year + '년 ' + month + '월';

    // 통계 초기화
    document.getElementById('month_stat_count').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

    // 오늘 날짜
    var now   = new Date();
    var today = now.getFullYear() + '-' +
        String(now.getMonth() + 1).padStart(2, '0') + '-' +
        String(now.getDate()).padStart(2, '0');

    var firstDay  = new Date(year, month - 1, 1);
    var daysTotal = new Date(year, month, 0).getDate();
    var startDow  = firstDay.getDay(); // 0=일 ... 6=토

    var html = '';

    // 1일 이전 빈 셀
    for (var i = 0; i < startDow; i++) {
        html += '<div class="cal_cell empty"></div>';
    }

    // 날짜 셀
    for (var d = 1; d <= daysTotal; d++) {
        var dateStr = year + '-' +
            String(month).padStart(2, '0') + '-' +
            String(d).padStart(2, '0');
        var dow     = (startDow + d - 1) % 7;
        var classes = ['cal_cell'];
        if (dateStr === today) classes.push('today');
        if (dow === 0)         classes.push('sun');
        if (dow === 6)         classes.push('sat');

        html += '<div class="' + classes.join(' ') + '" data-date="' + dateStr + '">' +
            '<div><span class="cal_date">' + d + '</span></div>' +
            '</div>';
    }

    // 마지막 주 나머지 빈 셀
    var cell      = startDow + daysTotal;
    var remaining = (7 - (cell % 7)) % 7;
    for (var j = 0; j < remaining; j++) {
        html += '<div class="cal_cell empty"></div>';
    }

    document.getElementById('cal_grid').innerHTML = html;
}

function loadCalendar(year, month) {
    $.ajax({
        url: '/api/v1/workout-logs/calendar',
        type: 'GET',
        data: { year: year, month: month },
        success: function (res) {
            if (res.code === 'SUCCESS') {
                document.getElementById('login_notice_wrap').style.display = 'none';
                renderCalendar(res.data, res.total_count);
            } else if (res.code === 'MEMBER_ONLY') {
                document.getElementById('login_notice_wrap').style.display = '';
                $('#month_stat_count').text('0');
            } else {
                renderCalendar({}, 0);
            }
        },
        error: function () {
            $('#month_stat_count').text('?');
        }
    });
}

function renderCalendar(workoutMap, totalCount) {
    $('#month_stat_count').text(totalCount);

    $('.cal_cell[data-date]').each(function () {
        var $cell = $(this);
        var date  = $cell.data('date');
        var log   = workoutMap[date];

        if (!log) return;

        $cell.addClass('has_log');

        var inner = '<a href="/workout_log/view/?id=' + log.id + '">';
        inner += '<span class="cal_date">' + $cell.find('.cal_date').text() + '</span>';
        inner += '<span class="workout_dot" title="' + escHtml(log.title) + '"></span>';
        if (log.title) {
            inner += '<span class="workout_summary">' + escHtml(log.title) + '</span>';
        }
        if (log.count > 1) {
            inner += '<span class="workout_count_badge">\u00d7' + log.count + '</span>';
        }
        inner += '</a>';

        $cell.html(inner);
    });
}

function escHtml(str) {
    return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
