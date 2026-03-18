<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class WorkoutLog {

    // =========================================================
    // 목록 조회
    // =========================================================

    /**
     * 득근일지 목록 (페이징)
     */
    public static function getList(int $accountId, int $startRow, int $rows): array {
        global $PDO;
        $sql = "
            Select
                wl.id,
                wl.workout_date,
                wl.workout_duration,
                wl.memo,
                wl.create_datetime,
                Count(Distinct we.id) as exercise_count,
                Group_Concat(we.exercise_name Order by we.order_no Separator ', ') as exercise_summary
            From WorkoutLog wl
            Left Join WorkoutLogExercise we On we.log_id = wl.id
            Where wl.account_id = :account_id
              And wl.is_delete = 0
            Group By wl.id
            Order by wl.workout_date Desc, wl.id Desc
            Limit :start_row, :rows
        ";
        $param = [
            ":account_id" => $accountId,
            ":start_row"  => $startRow,
            ":rows"       => $rows,
        ];
        return $PDO->fetchAll($sql, $param) ?: [];
    }

    /**
     * 특정 연월의 운동 날짜 목록 조회 (달력용)
     * 반환: [ ['id' => ..., 'workout_date' => 'YYYY-MM-DD', 'exercise_summary' => '...'], ... ]
     */
    public static function getMonthDates(int $accountId, int $year, int $month): array {
        global $PDO;
        $ym = sprintf('%04d-%02d', $year, $month);
        $sql = "
            Select
                wl.id,
                wl.workout_date,
                wl.workout_duration,
                Group_Concat(we.exercise_name Order by we.order_no Separator ', ') as exercise_summary
            From WorkoutLog wl
            Left Join WorkoutLogExercise we On we.log_id = wl.id
            Where wl.account_id = :account_id
              And wl.is_delete = 0
              And wl.workout_date Like :ym
            Group By wl.id
            Order By wl.workout_date Asc
        ";
        return $PDO->fetchAll($sql, [
            ':account_id' => $accountId,
            ':ym'         => $ym . '-%',
        ]) ?: [];
    }

    /**
     * 득근일지 총 개수
     */
    public static function getCount(int $accountId): int {
        global $PDO;
        $sql = "
            Select Count(*) as cnt
            From WorkoutLog
            Where account_id = :account_id
              And is_delete = 0
        ";
        $param = [":account_id" => $accountId];
        return (int)($PDO->fetch($sql, $param)["cnt"] ?? 0);
    }

    // =========================================================
    // 단건 조회
    // =========================================================

    /**
     * 일지 기본 정보 조회
     */
    public static function getById(int $logId): ?array {
        global $PDO;
        $sql = "
            Select *
            From WorkoutLog
            Where id = :id
              And is_delete = 0
        ";
        $param  = [":id" => $logId];
        $result = $PDO->fetch($sql, $param);
        return $result ?: null;
    }

    /**
     * 일지에 속한 종목 + 세트 전체 조회
     * 반환: [ { id, exercise_name, order_no, sets: [{set_no, weight, reps}, ...] }, ... ]
     */
    public static function getDetail(int $logId): array {
        global $PDO;

        // 종목 목록
        $sqlEx = "
            Select id, exercise_name, order_no
            From WorkoutLogExercise
            Where log_id = :log_id
            Order by order_no Asc
        ";
        $exercises = $PDO->fetchAll($sqlEx, [":log_id" => $logId]) ?: [];

        if (empty($exercises)) return [];

        // 세트 목록 (한번에 조회)
        $exerciseIds = array_column($exercises, 'id');
        $placeholders = implode(',', array_fill(0, count($exerciseIds), '?'));
        $sqlSet = "
            Select id, exercise_id, set_no, weight, reps
            From WorkoutLogSet
            Where exercise_id In ({$placeholders})
            Order by exercise_id Asc, set_no Asc
        ";
        $allSets = $PDO->fetchAll($sqlSet, $exerciseIds) ?: [];

        // 세트를 exercise_id 기준으로 그룹핑
        $setsMap = [];
        foreach ($allSets as $set) {
            $setsMap[$set['exercise_id']][] = $set;
        }

        foreach ($exercises as &$ex) {
            $ex['sets'] = $setsMap[$ex['id']] ?? [];
        }
        unset($ex);

        return $exercises;
    }

    // =========================================================
    // 등록 / 수정
    // =========================================================

    /**
     * 일지 신규 등록
     * @return int 생성된 log ID
     */
    public static function insertLog(int $accountId, string $workoutDate, ?int $duration, string $memo, string $weightUnit = 'kg'): int {
        global $PDO;
        $sql = "
            Insert Into WorkoutLog (account_id, workout_date, workout_duration, memo, weight_unit)
            Values (:account_id, :workout_date, :workout_duration, :memo, :weight_unit)
        ";
        $param = [
            ":account_id"       => $accountId,
            ":workout_date"     => $workoutDate,
            ":workout_duration" => $duration,
            ":memo"             => $memo,
            ":weight_unit"      => in_array($weightUnit, ['kg', 'lb']) ? $weightUnit : 'kg',
        ];
        return (int)$PDO->execute($sql, $param);
    }

    /**
     * 일지 기본 정보 수정
     */
    public static function updateLog(int $logId, string $workoutDate, ?int $duration, string $memo, string $weightUnit = 'kg'): void {
        global $PDO;
        $sql = "
            Update WorkoutLog
            Set workout_date     = :workout_date,
                workout_duration = :workout_duration,
                memo             = :memo,
                weight_unit      = :weight_unit
            Where id = :id
        ";
        $param = [
            ":workout_date"     => $workoutDate,
            ":workout_duration" => $duration,
            ":memo"             => $memo,
            ":weight_unit"      => in_array($weightUnit, ['kg', 'lb']) ? $weightUnit : 'kg',
            ":id"               => $logId,
        ];
        $PDO->execute($sql, $param);
    }

    /**
     * 종목 등록
     * @return int 생성된 exercise ID
     */
    public static function insertExercise(int $logId, string $exerciseName, int $orderNo): int {
        global $PDO;
        $sql = "
            Insert Into WorkoutLogExercise (log_id, exercise_name, order_no)
            Values (:log_id, :exercise_name, :order_no)
        ";
        $param = [
            ":log_id"        => $logId,
            ":exercise_name" => $exerciseName,
            ":order_no"      => $orderNo,
        ];
        return (int)$PDO->execute($sql, $param);
    }

    /**
     * 세트 등록
     */
    public static function insertSet(int $exerciseId, int $setNo, float $weight, int $reps): void {
        global $PDO;
        $sql = "
            Insert Into WorkoutLogSet (exercise_id, set_no, weight, reps)
            Values (:exercise_id, :set_no, :weight, :reps)
        ";
        $param = [
            ":exercise_id" => $exerciseId,
            ":set_no"      => $setNo,
            ":weight"      => $weight,
            ":reps"        => $reps,
        ];
        $PDO->execute($sql, $param);
    }

    /**
     * 수정 시 기존 종목 + 세트 전체 삭제 후 재등록 방식
     * 종목/세트를 전부 지우고 새로 insert
     */
    public static function deleteExercisesAndSets(int $logId): void {
        global $PDO;

        // 해당 log의 exercise_id 목록
        $sqlEx = "Select id From WorkoutLogExercise Where log_id = :log_id";
        $exercises = $PDO->fetchAll($sqlEx, [":log_id" => $logId]) ?: [];

        if (!empty($exercises)) {
            $ids = array_column($exercises, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // 세트 먼저 삭제
            $PDO->execute("Delete From WorkoutLogSet Where exercise_id In ({$placeholders})", $ids);
        }

        // 종목 삭제
        $PDO->execute("Delete From WorkoutLogExercise Where log_id = :log_id", [":log_id" => $logId]);
    }

    // =========================================================
    // 삭제
    // =========================================================

    /**
     * 일지 소프트 삭제
     */
    public static function deleteLog(int $logId): void {
        global $PDO;
        $sql = "Update WorkoutLog Set is_delete = 1 Where id = :id";
        $PDO->execute($sql, [":id" => $logId]);
    }

}
