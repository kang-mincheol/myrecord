<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

echo css_load('/workout_log/view/index.css');

if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}

$log_id = (int)preg_replace("/[^0-9]+/u", "", $_GET['id'] ?? '');
if(!$log_id) {
    echo '<script>myrecordAlert(\'on\', \'잘못된 접근입니다\', \'알림\', \'location.href="/workout_log/list/"\');</script>';
    exit;
}

$log = WorkoutLog::getById($log_id);
if(!$log || (int)$log['account_id'] !== (int)$member['id']) {
    echo '<script>myrecordAlert(\'on\', \'접근 권한이 없습니다\', \'알림\', \'location.href="/workout_log/list/"\');</script>';
    exit;
}

$exercises = WorkoutLog::getDetail($log_id);

// 날짜 포맷
$workoutDate = new DateTime($log['workout_date']);
$days = ['일', '월', '화', '수', '목', '금', '토'];
$dateFormatted = $workoutDate->format('Y년 n월 j일') . ' (' . $days[(int)$workoutDate->format('w')] . ')';
$weightUnit = $log['weight_unit'] ?? 'kg';
?>

<div class="workout_log_header">
    <div class="header_inner">
        <p class="page_title"><?= htmlspecialchars($dateFormatted) ?></p>
        <?php if($log['workout_duration']): ?>
        <p class="page_subtitle"><i class="fa-regular fa-clock"></i> <?= (int)$log['workout_duration'] ?>분 운동</p>
        <?php endif; ?>
    </div>
</div>

<div class="workout_view_wrap">

    <!-- 단위 토글 -->
    <div class="view_unit_toggle_wrap">
        <span class="view_unit_label"><i class="fa-solid fa-weight-hanging"></i> 무게 단위</span>
        <div class="unit_toggle_wrap">
            <button type="button" class="unit_toggle_btn <?= $weightUnit === 'kg' ? 'active' : '' ?>" data-unit="kg" onclick="toggleUnit('kg', this);">KG</button>
            <button type="button" class="unit_toggle_btn <?= $weightUnit === 'lb' ? 'active' : '' ?>" data-unit="lb" onclick="toggleUnit('lb', this);">LB</button>
        </div>
    </div>

    <!-- 종목 카드들 -->
    <?php foreach($exercises as $idx => $ex): ?>
    <div class="exercise_card">
        <div class="exercise_card_title">
            <span class="exercise_order"><?= $idx + 1 ?></span>
            <span class="exercise_name"><?= htmlspecialchars($ex['exercise_name']) ?></span>
        </div>

        <table class="set_table">
            <thead>
                <tr>
                    <th>세트</th>
                    <th>무게</th>
                    <th>횟수</th>
                    <th>볼륨</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalVolume = 0;
                foreach($ex['sets'] as $set):
                    $volume = (float)$set['weight'] * (int)$set['reps'];
                    $totalVolume += $volume;
                ?>
                <tr>
                    <td class="set_no_cell"><?= (int)$set['set_no'] ?>set</td>
                    <td class="weight_cell" data-weight="<?= (float)$set['weight'] ?>">
                        <span class="weight_val"><?= number_format((float)$set['weight'], 1) ?></span><span class="td_unit"><?= $weightUnit ?></span>
                    </td>
                    <td class="reps_cell"><?= (int)$set['reps'] ?><span class="td_unit">회</span></td>
                    <td class="volume_cell" data-weight="<?= (float)$set['weight'] ?>" data-reps="<?= (int)$set['reps'] ?>">
                        <span class="volume_val"><?= number_format($volume, 1) ?></span><span class="td_unit"><?= $weightUnit ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total_row" data-total-volume="<?= $totalVolume ?>">
                    <td colspan="3">총 볼륨</td>
                    <td><span class="total_vol_val"><?= number_format($totalVolume, 1) ?></span><span class="td_unit"><?= $weightUnit ?></span></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php endforeach; ?>


    <!-- 메모 -->
    <?php if(!empty($log['memo'])): ?>
    <div class="memo_card">
        <div class="memo_card_title"><i class="fa-regular fa-note-sticky"></i> 메모</div>
        <p class="memo_content"><?= nl2br(htmlspecialchars($log['memo'])) ?></p>
    </div>
    <?php endif; ?>


    <!-- 하단 버튼 -->
    <div class="view_footer">
        <a href="/workout_log/list/" class="list_btn"><i class="fa-solid fa-list"></i> 목록</a>
        <a href="/workout_log/edit/?id=<?= $log_id ?>" class="edit_btn"><i class="fa-solid fa-pen"></i> 수정</a>
        <button class="delete_btn" onclick="confirmDelete(<?= $log_id ?>);"><i class="fa-solid fa-trash"></i> 삭제</button>
    </div>

</div>
<!-- workout_view_wrap -->


<?php echo script_load('/workout_log/view/index.js'); ?>
<script>
var savedUnit = '<?= $weightUnit ?>';
$(function () { currentUnit = savedUnit; });
</script>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
