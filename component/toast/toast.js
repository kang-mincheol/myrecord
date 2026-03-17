/**
 * showToast(message, type, duration)
 * type: 'success' | 'error' | 'info'  (default: 'info')
 * duration: ms  (default: 3000)
 */
function showToast(message, type, duration) {
    type     = type     || 'info';
    duration = duration || 3000;

    var iconMap = {
        success: 'fa-circle-check',
        error:   'fa-circle-xmark',
        info:    'fa-circle-info'
    };

    var container = document.getElementById('toast_container');
    var toast     = document.createElement('div');
    toast.className = 'toast_item toast_' + type;
    toast.innerHTML = '<i class="fa-solid ' + (iconMap[type] || iconMap.info) + '"></i>' + message;

    container.appendChild(toast);

    // 다음 프레임에서 show 클래스 추가 (트랜지션 발동)
    requestAnimationFrame(function() {
        requestAnimationFrame(function() {
            toast.classList.add('show');
        });
    });

    setTimeout(function() {
        toast.classList.remove('show');
        setTimeout(function() {
            toast.remove();
        }, 300);
    }, duration);
}
