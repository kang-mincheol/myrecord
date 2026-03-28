<?php

class WorkoutLogsController {

    /**
     * GET /api/v1/workout-logs?page=1
     */
    public static function list(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $page      = max(1, (int)($_GET['page'] ?? 1));
        $page_size = 10;
        $start_row = ($page - 1) * $page_size;

        $list        = WorkoutLog::getList($member["id"], $start_row, $page_size);
        $total_count = WorkoutLog::getCount($member["id"]);

        if (empty($list)) {
            $returnArray["code"] = "EMPTY";
            $returnArray["msg"]  = "등록된 득근일지가 없습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $returnArray["total_count"] = $total_count;
        $returnArray["page"]        = $page;
        $returnArray["page_size"]   = $page_size;
        $returnArray["data"]        = [];

        foreach ($list as $row) {
            $returnArray["data"][] = [
                "id"               => $row["id"],
                "title"            => $row["title"] ?? '',
                "workout_date"     => $row["workout_date"],
                "workout_duration" => $row["workout_duration"],
                "memo"             => $row["memo"] ?? '',
                "exercise_count"   => $row["exercise_count"],
                "exercise_summary" => $row["exercise_summary"] ?? '',
                "create_datetime"  => $row["create_datetime"],
            ];
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/workout-logs/calendar?year=2026&month=3
     */
    public static function calendar(array $params): void {
        global $is_member, $member;

        if (!$is_member) {
            echo json_encode(["code" => "MEMBER_ONLY", "msg" => "로그인 후 이용해주세요"], JSON_UNESCAPED_UNICODE);
            return;
        }

        $year  = (int)($_GET['year']  ?? date('Y'));
        $month = (int)($_GET['month'] ?? date('n'));

        if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
            echo json_encode(["code" => "PARAM_ERROR", "msg" => "잘못된 파라미터입니다"], JSON_UNESCAPED_UNICODE);
            return;
        }

        $logs = WorkoutLog::getMonthDates($member['id'], $year, $month);

        $workout_map = [];
        foreach ($logs as $log) {
            $date = $log['workout_date'];
            if (!isset($workout_map[$date])) {
                $workout_map[$date] = [
                    'id'    => (int)$log['id'],
                    'title' => $log['title'] ?? '',
                    'count' => 1,
                ];
            } else {
                $workout_map[$date]['count']++;
            }
        }

        echo json_encode([
            "code"        => "SUCCESS",
            "year"        => $year,
            "month"       => $month,
            "total_count" => count($logs),
            "data"        => $workout_map,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/workout-logs/{id}
     */
    public static function view(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $log_id = (int)$params["id"];
        $log    = WorkoutLog::getById($log_id);

        if (!$log) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "데이터를 찾을 수 없습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if ((int)$log["account_id"] !== (int)$member["id"]) {
            $returnArray["code"] = "FORBIDDEN";
            $returnArray["msg"]  = "접근 권한이 없습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $exercises = WorkoutLog::getDetail($log_id);

        $returnArray["data"] = [
            "id"               => $log["id"],
            "title"            => $log["title"] ?? '',
            "workout_date"     => $log["workout_date"],
            "workout_duration" => $log["workout_duration"],
            "weight_unit"      => $log["weight_unit"] ?? 'kg',
            "memo"             => $log["memo"] ?? '',
            "exercises"        => $exercises,
        ];

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * POST /api/v1/workout-logs
     * Body: { "title", "workout_date", "exercises", "workout_duration"?, "memo"?, "weight_unit"? }
     */
    public static function create(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["workout_date", "exercises"])) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $validation_error = self::validateWorkoutData($data);
        if ($validation_error) {
            echo json_encode($validation_error, JSON_UNESCAPED_UNICODE); return;
        }

        [$title, $workout_date, $workout_duration, $memo, $weight_unit, $exercises] = self::extractWorkoutFields($data);

        $log_id = WorkoutLog::insertLog($member["id"], $title, $workout_date, $workout_duration, $memo, $weight_unit);

        self::saveExercises($log_id, $exercises);

        http_response_code(201);
        $returnArray["log_id"] = $log_id;
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * PUT /api/v1/workout-logs/{id}
     * Body: { "title", "workout_date", "exercises", "workout_duration"?, "memo"?, "weight_unit"? }
     */
    public static function update(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $log_id = (int)$params["id"];
        $data   = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["workout_date", "exercises"])) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $existing = WorkoutLog::getById($log_id);
        if (!$existing) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "데이터를 찾을 수 없습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if ((int)$existing["account_id"] !== (int)$member["id"]) {
            $returnArray["code"] = "FORBIDDEN";
            $returnArray["msg"]  = "접근 권한이 없습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $validation_error = self::validateWorkoutData($data);
        if ($validation_error) {
            echo json_encode($validation_error, JSON_UNESCAPED_UNICODE); return;
        }

        [$title, $workout_date, $workout_duration, $memo, $weight_unit, $exercises] = self::extractWorkoutFields($data);

        WorkoutLog::updateLog($log_id, $title, $workout_date, $workout_duration, $memo, $weight_unit);
        WorkoutLog::deleteExercisesAndSets($log_id);

        self::saveExercises($log_id, $exercises);

        $returnArray["log_id"] = $log_id;
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * DELETE /api/v1/workout-logs/{id}
     */
    public static function delete(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $log_id = (int)$params["id"];
        $log    = WorkoutLog::getById($log_id);

        if (!$log) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "데이터를 찾을 수 없습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if ((int)$log["account_id"] !== (int)$member["id"]) {
            $returnArray["code"] = "FORBIDDEN";
            $returnArray["msg"]  = "접근 권한이 없습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        WorkoutLog::deleteLog($log_id);

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    // -------------------------
    // Private helpers
    // -------------------------

    private static function validateWorkoutData(array $data): ?array {
        $title = isset($data["title"]) ? mb_substr(trim($data["title"]), 0, 100) : '';
        if ($title === '') {
            return ["code" => "PARAM_ERROR", "msg" => "제목을 입력해주세요"];
        }

        $workout_date = trim($data["workout_date"] ?? '');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $workout_date)) {
            return ["code" => "PARAM_ERROR", "msg" => "운동 날짜를 올바르게 입력해주세요"];
        }

        $workout_duration = isset($data["workout_duration"]) && $data["workout_duration"] !== '' && $data["workout_duration"] !== null
            ? (int)preg_replace("/[^0-9]+/u", "", $data["workout_duration"])
            : null;

        if ($workout_duration !== null && ($workout_duration < 1 || $workout_duration > 1440)) {
            return ["code" => "PARAM_ERROR", "msg" => "운동 시간은 1분 ~ 1440분 사이로 입력해주세요"];
        }

        $exercises = $data["exercises"] ?? [];
        if (empty($exercises) || !is_array($exercises)) {
            return ["code" => "PARAM_ERROR", "msg" => "운동 종목을 1개 이상 입력해주세요"];
        }

        if (count($exercises) > 20) {
            return ["code" => "PARAM_ERROR", "msg" => "운동 종목은 최대 20개까지 입력 가능합니다"];
        }

        foreach ($exercises as $idx => $ex) {
            $exName = trim($ex["exercise_name"] ?? '');
            if ($exName === '') {
                return ["code" => "PARAM_ERROR", "msg" => ($idx + 1) . "번째 종목명을 입력해주세요"];
            }
            $sets = $ex["sets"] ?? [];
            if (empty($sets) || !is_array($sets)) {
                return ["code" => "PARAM_ERROR", "msg" => $exName . " 종목의 세트를 1개 이상 입력해주세요"];
            }
            foreach ($sets as $sIdx => $set) {
                $weight = $set["weight"] ?? '';
                $reps   = $set["reps"]   ?? '';
                if ($weight === '' || $reps === '') {
                    return ["code" => "PARAM_ERROR", "msg" => $exName . " " . ($sIdx + 1) . "세트의 무게/횟수를 입력해주세요"];
                }
            }
        }

        return null;
    }

    private static function extractWorkoutFields(array $data): array {
        $title            = mb_substr(trim($data["title"]), 0, 100);
        $workout_date     = trim($data["workout_date"]);
        $workout_duration = isset($data["workout_duration"]) && $data["workout_duration"] !== '' && $data["workout_duration"] !== null
            ? (int)preg_replace("/[^0-9]+/u", "", $data["workout_duration"])
            : null;
        $memo         = isset($data["memo"]) ? trim($data["memo"]) : '';
        if (mb_strlen($memo) > 500) $memo = mb_substr($memo, 0, 500);
        $weight_unit  = isset($data["weight_unit"]) && $data["weight_unit"] === 'lb' ? 'lb' : 'kg';
        $exercises    = $data["exercises"];

        return [$title, $workout_date, $workout_duration, $memo, $weight_unit, $exercises];
    }

    private static function saveExercises(int $log_id, array $exercises): void {
        foreach ($exercises as $orderNo => $ex) {
            $exName     = mb_substr(trim($ex["exercise_name"]), 0, 100);
            $exerciseId = WorkoutLog::insertExercise($log_id, $exName, $orderNo + 1);
            foreach ($ex["sets"] as $sIdx => $set) {
                $weight = (float)preg_replace("/[^0-9.]+/u", "", $set["weight"] ?? '0');
                $reps   = (int)preg_replace("/[^0-9]+/u", "", $set["reps"]   ?? '0');
                WorkoutLog::insertSet($exerciseId, $sIdx + 1, $weight, $reps);
            }
        }
    }
}
