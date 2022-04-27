<?php
// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define('NO_ALONE', true);

define('SERVER_IP', "11.11.11.11");

//운영서버여부
define('IS_LIVE' , gethostbyname($_SERVER["HTTP_HOST"]) == SERVER_IP);

//로컬여부
define('IS_LOCAL' , strpos($_SERVER["HTTP_HOST"], "localhost") !== false);

//DB 연결
define('MYSQL_HOST', IS_LIVE && IS_LOCAL ? '11.11.11.11:3306' : 'localhost');

define('MYSQL_USER', IS_LIVE ? 'myrecord' : 'myrecord');
define('MYSQL_PASSWORD', IS_LIVE ? 'myrecord2022!' : 'myrecord2022!');
define('MYSQL_DB', 'myrecord'); 

define('SESSION_PATH', "/var/lib/php/sessions");

define('SITE_URL', (IS_LIVE ? "https" : "http").'://'.$_SERVER["HTTP_HOST"]);

define('ALLOW_FILES', "jpg|png|jpeg|gif|pdf|hwp|xls|xlsx|doc|docx|mp4");

define('BACK_URL', IS_LIVE ? "/" : "http://localhost:8080/");

$con = sql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

sql_query("use myrecord");
sql_query("set names utf8");

// DB 연결
function sql_connect($host, $user, $pass, $db)
{
	if(function_exists('mysqli_connect')) {
        $link = mysqli_connect($host, $user, $pass, $db, "3360");

        // 연결 오류 발생 시 스크립트 종료
        if (mysqli_connect_errno()) {
            die('Connect Error: '.mysqli_connect_error());
        }
    } 
    // else {
    //     $link = mysql_connect($host, $user, $pass);
    // }

    return $link;
}

// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql)
{
    global $con;
    $result = sql_query($sql);
    $row = sql_fetch_array($result);
    return $row;
}

function sql_query($sql)
{
    global $con;
    
    $sql = trim($sql);
    // union의 사용을 허락하지 않습니다.
    //$sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
    $sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
    // `information_schema` DB로의 접근을 허락하지 않습니다.
    $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

    if(function_exists('mysqli_query')) {
        $result = @mysqli_query($con, $sql);
    } 
    // else {
    //     $result = @mysql_query($sql, $con);
    // }
    
    return $result;
}

function sql_query_identity($sql)
{
    global $con;
    
    $sql = trim($sql);
    // union의 사용을 허락하지 않습니다.
    //$sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
    $sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
    // `information_schema` DB로의 접근을 허락하지 않습니다.
    $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

    if(function_exists('mysqli_query')) {
        $result = @mysqli_query($con, $sql);
    } 
    // else {
    //     $result = @mysql_query($sql, $con);
    // }
    
    return $con->insert_id;
}

// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    if(function_exists('mysqli_fetch_assoc'))
        $row = @mysqli_fetch_assoc($result);
    // else
    //     $row = @mysql_fetch_assoc($result);

    return $row;
}




/********** PDO 설정 **********/
//$dsn = "mysql:host=localhost;port=3306;dbname=myrecord;charset=utf8";
//try {
//    $db = new PDO($dsn, "myrecord", "myrecord2022!");
//    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
//    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "데이터베이스 연결 성공!!<br/>";
//} catch(PDOException $e) {
//    echo $e->getMessage();
//}

$mysql_hostname = 'localhost';
$mysql_username = 'myrecord';
$mysql_password = 'myrecord2022!';
$mysql_database = 'myrecord';
$mysql_port = '3306';
$mysql_charset = 'utf8';


//1. DB 연결
$connect = new mysqli($mysql_hostname, $mysql_username, $mysql_password, $mysql_database, $mysql_port);

if($connect->connect_errno){
    echo '[연결실패] : '.$connect->connect_error.'';
} else {
    echo '[연결성공]';
}

/********** PDO 설정 END **********/
?>