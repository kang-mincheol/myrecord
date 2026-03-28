<?php

class RecordsController {

    /**
     * GET /api/v1/records?record_type=squat&page=1&search_key=nickname&search_keyword=xxx
     */
    public static function list(array $params): void {
        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $record_type = $_GET["record_type"] ?? '';

        if ($record_type === '') {
            if (IS_LIVE) {
                $returnArray["code"] = "PARAMS";
                $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $record_type = cleansingParams(["record_type" => $record_type])["record_type"];

        $master = Record::getMasterByNameLower($record_type);
        if (!$master) {
            $returnArray["code"] = "RECORD_TYPE_ERROR";
            $returnArray["msg"]  = "잘못된 값 입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }
        $record_id = (int)$master["id"];

        $page      = max(1, (int)($_GET["page"] ?? 1));
        $rows      = 10;
        $start_row = ($page - 1) * $rows;

        $search_key     = $_GET["search_key"]     ?? '';
        $search_keyword = $_GET["search_keyword"] ?? '';

        if ($search_key && $search_keyword) {
            if (strlen($search_keyword) < 1) {
                $returnArray["code"] = "KEYWORD_ERROR";
                $returnArray["msg"]  = "검색시 두글자 이상 입력해 주세요";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
            if (!in_array($search_key, ["nickname", "weight"])) {
                $returnArray["code"] = "SEARCH_KEY_ERROR";
                $returnArray["msg"]  = "잘못된 값 입니다";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $list = Record::getBoardList($record_id, $start_row, $rows, $search_key, $search_keyword);

        if (!$list) {
            $returnArray["code"] = "EMPTY";
            $returnArray["msg"]  = "데이터가 없습니다<br/>데이터가 잘못된 경우 고객센터에 문의해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        foreach ($list as $value) {
            $returnArray["data"][] = [
                "record_id"     => $value["id"],
                "nickname"      => $value["user_nickname"],
                "record_weight" => $value["record_weight"],
                "record_status" => $value["status_text"],
                "date"          => date("Y.m.d", strtotime($value["create_datetime"])),
            ];
        }

        $total_count = Record::getBoardCount($record_id);
        $max_page    = (int)ceil($total_count / $rows);
        $start_page  = (int)floor(($page - 1) / $rows) * $rows;

        $page_arr = [];
        for ($i = 1; $i <= 10; $i++) {
            $this_page = $start_page + $i;
            if ($this_page <= $max_page) {
                $page_arr[] = $this_page;
            }
        }
        $returnArray["page"] = $page_arr;

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/records/me
     */
    public static function getMe(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $record_masters = Record::getRecordType();

        foreach ($record_masters as $master) {
            $record = Record::getLatestRecordByType($member["id"], $master["id"]);

            if ($record) {
                $status_text  = "";
                $status_color = "";
                if ($record["status"] == 0) {
                    $status_text  = "신청 완료";
                    $status_color = "blue";
                } elseif ($record["status"] == 1) {
                    $status_text  = "심사중";
                    $status_color = "blue";
                } elseif ($record["status"] == 2) {
                    $status_text  = "심사 완료";
                    $status_color = "black";
                } elseif ($record["status"] == 9) {
                    $status_text  = "심사 반려";
                    $status_color = "red";
                }

                $returnArray["data"][] = [
                    "type"         => $master["id"],
                    "type_name"    => $master["record_name"],
                    "record_id"    => $record["id"],
                    "weight"       => $record["record_weight"] . "KG",
                    "status"       => $status_text,
                    "status_color" => $status_color,
                ];
            } else {
                $returnArray["data"][] = [
                    "type"      => $master["id"],
                    "type_name" => $master["record_name"],
                ];
            }
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/records/ranking?record_type=squat
     */
    public static function ranking(array $params): void {
        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $record_type = $_GET["record_type"] ?? '';

        if ($record_type === '') {
            if (IS_LIVE) {
                $returnArray["code"] = "PARAMS";
                $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $record_type = preg_replace("/[^A-Za-z]+/u", "", $record_type);
        $record_id   = Record::getMasterIdByName($record_type);

        if (!$record_id) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "올바르지 않은 값입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $ranking = Record::getRankingByTypeId($record_id);

        if (!$ranking) {
            $returnArray["code"] = "EMPTY";
            $returnArray["msg"]  = "데이터가 없습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        foreach ($ranking as $value) {
            $returnArray["data"][] = [
                "weight"    => $value["weight"],
                "nickname"  => $value["user_nickname"],
                "record_id" => $value["id"],
            ];
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/records/ranking/total
     */
    public static function rankingTotal(array $params): void {
        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $total_ranking = Record::getTotalRanking();

        if (!$total_ranking) {
            $returnArray["code"] = "EMPTY";
            $returnArray["msg"]  = "데이터가 없습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        foreach ($total_ranking as $value) {
            $returnArray["data"][] = [
                "3대"                  => $value["total_sum"],
                "squat"                => $value["squat"],
                "squat_record_id"      => $value["squat_id"],
                "benchpress"           => $value["bench"],
                "benchpress_record_id" => $value["bench_id"],
                "deadlift"             => $value["dead"],
                "deadlift_record_id"   => $value["dead_id"],
                "nickname"             => $value["user_nickname"],
            ];
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/records/{id}
     */
    public static function view(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $record_id   = (int)$params["id"];
        $record_data = Record::getRecordViewData($record_id);

        if (!$record_data) {
            $returnArray["code"] = "PARAM_ERROR";
            $returnArray["msg"]  = "올바르지 않은 데이터입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $returnArray["data"] = [
            "record_nickname"   => $record_data["user_nickname"],
            "record_name"       => $record_data["record_name_ko"],
            "record_weight"     => $record_data["record_weight"],
            "record_memo"       => $record_data["memo"] ?? '',
            "record_status"     => $record_data["status_text"],
            "record_status_eng" => $record_data["status_value"],
            "record_create"     => $record_data["create_date"],
            "is_recorder"       => $is_member && $record_data["account_id"] == $member["id"],
        ];

        $files = Record::getFilesByRequestId($record_id);
        if ($files) {
            foreach ($files as $row) {
                $returnArray["file"][] = [
                    "file_name" => $row["file_guid"],
                    "file_src"  => RECORD_FILE_DIR . $row["file_guid"],
                    "file_type" => $row["file_type"],
                ];
            }
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/records/{id}/verify?code=XXXX-XXXX-XXXX-XXXX
     * Public endpoint — no auth required
     */
    public static function verify(array $params): void {
        global $PDO;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $record_id  = (int)$params["id"];
        $input_code = isset($_GET['code']) ? preg_replace('/[^A-F0-9-]/', '', strtoupper($_GET['code'])) : '';
        $input_raw  = str_replace('-', '', $input_code);

        if ($record_id <= 0 || $input_raw === '') {
            $returnArray["code"] = "INVALID";
            $returnArray["msg"]  = "잘못된 접근입니다. 인증서의 QR코드를 스캔해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $expected_raw = strtoupper(substr(hash('sha256', 'mr_cert_f7e2_' . $record_id), 0, 16));

        if ($input_raw !== $expected_raw) {
            $returnArray["code"] = "INVALID_CODE";
            $returnArray["msg"]  = "인증번호가 올바르지 않습니다. QR코드를 다시 스캔해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $sql = "
            Select  T1.id, T1.status, T2.user_nickname,
                    T1.record_weight, T3.record_name_ko,
                    T1.create_datetime  as request_datetime,
                    T4.create_datetime  as certificate_datetime
            From    tb_record_request T1
            Inner Join Account T2 On T1.account_id = T2.id
            Inner Join tb_record_master T3 On T1.record_type = T3.id
            Left Outer Join tb_record_inspection T4
                On  T1.id = T4.request_id
                And T4.change_status = '2'
            Where   T1.id = :id
            And     T1.status = 2
        ";

        $record_data = $PDO->fetch($sql, [':id' => $record_id]);

        if (!$record_data) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "해당 기록을 찾을 수 없거나 아직 승인되지 않은 기록입니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $cert_date = $record_data['certificate_datetime']
            ? date('Y년 m월 d일', strtotime($record_data['certificate_datetime']))
            : date('Y년 m월 d일', strtotime($record_data['request_datetime']));

        $returnArray["data"] = [
            "nickname"      => $record_data['user_nickname'],
            "record_name"   => $record_data['record_name_ko'],
            "record_weight" => $record_data['record_weight'],
            "cert_date"     => $cert_date,
            "cert_code"     => self::generateCertCode($record_id),
        ];

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/records/{id}/edit
     */
    public static function editData(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $record_id   = (int)$params["id"];
        $record_data = Record::getRecordById($record_id);

        if (!$record_data) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "잘못된 값 입니다.<br/>-2";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if ($member["id"] != $record_data["account_id"]) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "잘못된 값 입니다.<br/>-3";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if ($record_data["status"] == '2') {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "승인 완료된 마이레코드는 수정이 불가합니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        } elseif ($record_data["status"] == '1') {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "현재 심사중으로 수정이 불가합니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $returnArray["data"] = [
            "type"   => $record_data["record_type"],
            "weight" => $record_data["record_weight"],
            "memo"   => $record_data["memo"] ?? '',
            "status" => $record_data["status"],
        ];

        $files = Record::getFilesByRequestId($record_data["id"]);
        if ($files) {
            foreach ($files as $row) {
                $returnArray["data"]["file"][] = [
                    "file_name" => $row["file_original_name"],
                    "file_id"   => $row["file_guid"],
                    "file_no"   => $row["id"],
                    "file_type" => $row["file_type"],
                ];
            }
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/records/{id}/certificate
     */
    public static function certificate(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $record_id   = (int)$params["id"];
        $record_data = Record::getCertificateData($record_id);

        if (!$record_data) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "잘못된 값 입니다.<br/>-2";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if ($member["id"] != $record_data["account_id"]) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "잘못된 값 입니다.<br/>-3";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if ($record_data["status"] != 2) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "마이레코드 인증서는 승인 후 확인가능합니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $cert_code = self::generateCertCode($record_id);

        $returnArray["data"] = [
            "record_id"     => $record_id,
            "nickname"      => $record_data["user_nickname"],
            "record_type"   => $record_data["record_name_ko"],
            "record_weight" => $record_data["record_weight"] . "KG",
            "date"          => $record_data["certificate_datetime"]
                ? date("Y.m.d", strtotime($record_data["certificate_datetime"]))
                : date("Y.m.d", strtotime($record_data["request_datetime"])),
            "cert_code"     => $cert_code,
        ];

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * POST /api/v1/records
     * multipart/form-data: record_type, record_weight, record_memo, file(s)
     */
    public static function create(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data        = $_POST;
        $record_type = preg_replace('/[^0-9]+/u', '', $data['record_type'] ?? '');

        if ($record_type === '') {
            $returnArray["code"] = "PARAM_ERROR";
            $returnArray["msg"]  = "등록할 기록을 선택해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $valid_ids = array_column(Record::getRecordType(), 'id');
        if (!in_array($record_type, $valid_ids)) {
            $returnArray["code"] = "PARAM_ERROR";
            $returnArray["msg"]  = "잘못된 파라미터입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (Record::checkOverlapRequest($member["id"], $record_type, 0)) {
            $returnArray["code"] = "OVERLAP_REQUEST";
            $returnArray["msg"]  = "해당 종목으로 심사전 신청건이 존재합니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }
        if (Record::checkOverlapRequest($member["id"], $record_type, 1)) {
            $returnArray["code"] = "OVERLAP_REQUEST";
            $returnArray["msg"]  = "해당 종목으로 관리자가 심사중인 건이 존재합니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $record_weight = preg_replace('/[^0-9]+/u', '', $data['record_weight'] ?? '');
        if ($record_weight === '') {
            $returnArray["code"] = "PARAM_ERROR";
            $returnArray["msg"]  = "등록할 무게를 입력해 주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }
        if ($record_weight > 9999) {
            $returnArray["code"] = "PARAM_ERROR";
            $returnArray["msg"]  = "Record 무게를 확인해 주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $record_memo = isset($data['record_memo']) ? trim($data['record_memo']) : '';
        if (mb_strlen($record_memo) > 500) {
            $record_memo = mb_substr($record_memo, 0, 500);
        }

        $accessType = [
            "video/mp4","video/m4v","video/avi","video/wmv","video/mwa","video/asf",
            "video/mpg","video/mpeg","video/mkv","video/mov","video/3gp","video/3g2",
            "video/webm","video/quicktime","application/octet-stream",
            "image/jpeg","image/jpg","image/png",
        ];

        if (!$_FILES) {
            $returnArray["code"] = "FILE_EMPTY";
            $returnArray["msg"]  = "파일을 등록해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $total_file_size = 0;
        foreach ($_FILES as $value) {
            if (!in_array($value["type"], $accessType)) {
                $returnArray["code"] = "FILE_TYPE_LIMIT";
                $returnArray["msg"]  = "파일은 이미지 또는 동영상 파일만 업로드 가능합니다<br/>이미지 또는 동영상 파일이 업로드가 안될경우 고객센터에 문의부탁드립니다";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
            $total_file_size += $value["size"];
        }

        if (($total_file_size / 1024 / 1024) > 100) {
            $returnArray["code"] = "FILE_SIZE_LIMIT";
            $returnArray["msg"]  = "파일은 총 100MB 이하로 업로드 해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $request_id = Record::insertRecordRequest($member["id"], (int)$record_type, (int)$record_weight, $record_memo);

        $videoType   = [
            "video/mp4","video/m4v","video/avi","video/wmv","video/mwa","video/asf",
            "video/mpg","video/mpeg","video/mkv","video/mov","video/3gp","video/3g2",
            "video/webm","video/quicktime","application/octet-stream",
        ];
        $upload_path = $_SERVER["DOCUMENT_ROOT"] . "/data/record/";

        if (!is_dir($_SERVER["DOCUMENT_ROOT"] . "/data"))        @mkdir($_SERVER["DOCUMENT_ROOT"] . "/data");
        if (!is_dir($_SERVER["DOCUMENT_ROOT"] . "/data/record")) @mkdir($_SERVER["DOCUMENT_ROOT"] . "/data/record");

        foreach ($_FILES as $value) {
            $GUID = makeGuid();
            $ext  = "";
            if (in_array($value["type"], $videoType)) {
                if ($value["type"] === "video/quicktime") $value["type"] = "video/mp4";
                $ext = "." . explode("/", $value["type"])[1];
            }
            $file_guid = $GUID . $ext;

            Record::insertRecordFile($request_id, $value["name"], $file_guid, $value["type"]);
            move_uploaded_file($value["tmp_name"], $upload_path . $file_guid);
        }

        Slack::send(SLACK_URL_RECORD_INSERT, "record 신규 신청\n{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["HTTP_HOST"]}/record/squat/list/");

        http_response_code(201);
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * DELETE /api/v1/records/{id}
     */
    public static function delete(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $record_id   = (int)$params["id"];
        $record_data = Record::getRecordById($record_id);

        if (!$record_data) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "잘못된 값 입니다.<br/>-2";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if ($member["id"] != $record_data["account_id"]) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "잘못된 값 입니다.<br/>-3";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        Record::deleteRecord($record_id);

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/records/{id}/comments
     */
    public static function listComments(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $recordId = (int)$params["id"];

        if ($recordId <= 0) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (!Record::getRecordById($recordId)) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "기록을 찾을 수 없습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $comments = Record::getComments($recordId);
        $count    = Record::getCommentCount($recordId);

        $list = [];
        foreach ($comments as $c) {
            $list[] = [
                "id"              => (int)$c["id"],
                "contents"        => $c["contents"],
                "user_nickname"   => $c["user_nickname"],
                "create_datetime" => date("Y.m.d H:i", strtotime($c["create_datetime"])),
                "is_mine"         => $is_member ? ((int)$member["id"] === (int)$c["account_no"]) : false,
            ];
        }

        $returnArray["data"] = [
            "count" => $count,
            "list"  => $list,
        ];

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * POST /api/v1/records/{id}/comments
     * Body: { "contents": string }
     */
    public static function createComment(array $params): void {
        global $is_member;

        $returnArray = ["code" => "SUCCESS", "msg" => "댓글이 등록되었습니다."];

        if (!$is_member) {
            $returnArray["code"] = "LOGIN_REQUIRED";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $recordId = (int)$params["id"];
        $data     = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["contents"])) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data     = cleansingParams($data);
        $contents = trim($data["contents"]);

        if ($recordId <= 0 || !Record::getRecordById($recordId)) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "기록을 찾을 수 없습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (mb_strlen($contents) === 0) {
            $returnArray["code"] = "EMPTY_CONTENTS";
            $returnArray["msg"]  = "댓글 내용을 입력해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (mb_strlen($contents) > 500) {
            $returnArray["code"] = "TOO_LONG";
            $returnArray["msg"]  = "댓글은 500자 이내로 입력해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $commentId = Record::insertComment($recordId, $contents);

        http_response_code(201);
        $returnArray["comment_id"] = $commentId;
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * DELETE /api/v1/records/{id}/comments/{cid}
     */
    public static function deleteComment(array $params): void {
        global $is_member;

        $returnArray = ["code" => "SUCCESS", "msg" => "댓글이 삭제되었습니다."];

        if (!$is_member) {
            $returnArray["code"] = "LOGIN_REQUIRED";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $commentId = (int)$params["cid"];

        if ($commentId <= 0) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (!Record::isCommentOwner($commentId)) {
            $returnArray["code"] = "FORBIDDEN";
            $returnArray["msg"]  = "본인 댓글만 삭제할 수 있습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        Record::deleteComment($commentId);

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    private static function generateCertCode(int $id): string {
        $raw = strtoupper(substr(hash('sha256', 'mr_cert_f7e2_' . $id), 0, 16));
        return implode('-', str_split($raw, 4));
    }
}
