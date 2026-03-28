function loadCalendar(year, month) {
    $.ajax({
        url: '/api/v1/workout-logs/calendar',
        type: 'GET',
        data: { year: year, month: month },
        success: function (res) {
            if (res.code === 'SUCCESS') {
                renderCalendar(res.data, res.total_count);
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
