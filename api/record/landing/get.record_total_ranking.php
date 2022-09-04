<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);


$ranking_sql = "
    Select	Account.id, Account.user_id, Account.user_nickname,
		IfNull(Squat.weight, 0) as squat,
        IfNull(Squat.id, '-') as squat_id,
        IfNull(Benchpress.weight, 0) as bench,
        IfNull(Benchpress.id, '-') as bench_id,
        IfNull(Deadlift.weight, 0) as dead,
        IfNull(Deadlift.id, '-') as dead_id,
        Sum(
			IfNull(Squat.weight, 0) +
			IfNull(Benchpress.weight, 0) +
			IfNull(Deadlift.weight, 0)
		) as total_sum
    From	Account
    Left Outer Join (
        Select	max(record_weight) as weight, account_id, id
        From	tb_record_request
        Where	record_type = 1
        And		status = 2
        Group by account_id, id
    ) Squat
    On	Account.id = Squat.account_id
    Left Outer Join (
        Select	max(record_weight) as weight, account_id, id
        From	tb_record_request
        Where	record_type = 2
        And		status = 2
        Group by account_id, id
    ) Benchpress
    On	Account.id = Benchpress.account_id
    Left Outer Join (
        Select	max(record_weight) as weight, account_id, id
        From	tb_record_request
        Where	record_type = 3
        And		status = 2
        Group by account_id, id
    ) Deadlift
    On	Account.id = Deadlift.account_id

    Where	(Squat.weight > 0 Or Benchpress.weight > 0 Or Deadlift.weight)

    Group by Account.id, Account.user_id, Account.user_nickname, squat, bench, dead, squat_id, bench_id, dead_id

    Order by total_sum Desc
    Limit 0, 10
";
$total_ranking = $PDO -> fetchAll($ranking_sql);

if($total_ranking) {
    foreach($total_ranking as $key => $value) {
        $returnArray["data"][] = array(
            "3대" => $value["total_sum"],
            "squat" => $value["squat"],
            "sqaut_record_id" => $value["squat_id"],
            "benchpress" => $value["bench"],
            "benchpress_record_id" => $value["bench_id"],
            "deadlift" => $value["dead"],
            "deadlift_record_id" => $value["dead_id"],
            "nickname" => $value["user_nickname"]
        );
    }
} else {
    $returnArray = array(
        "code" => "EMPTY",
        "msg" => "데이터가 없습니다"
    );
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>