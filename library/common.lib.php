<?



// 세션변수 생성
function set_session($session_name, $value)
{
    $$session_name = $_SESSION[$session_name] = $value;
}


// 세션변수값 얻음
function get_session($session_name)
{
    return isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : '';
}

function getVersion($file_path)
{
    return date("YmdHis", filemtime($_SERVER['DOCUMENT_ROOT'] . $file_path));
}

//사용자 반환
function getMember_admin($id)
{
    $member = sql_fetch("
		Select  *
		From	Users
        Where	user_id = '{$id}'
	");

    return $member;
}

//사용자 반환
function getMember($user_id)
{
    $member = sql_fetch("
		Select  *
		From	Users
        Where	user_id = '{$user_id}'
	");

    return $member;
}

//사용자 정보 업데이트
function refreshMember()
{
    global $member;
    $member = getMember($member["id"]);
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
    setcookie(md5($cookie_name), base64_encode($value), time() + $expire, '/');
}

// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    $cookie = md5($cookie_name);
    if (array_key_exists($cookie, $_COOKIE))
        return base64_decode($_COOKIE[$cookie]);
    else
        return "";
}

//CSS 파일 로드 함수
function css_load($file_path) {
	//$file_path는 절대경로를 넣어준다
	$file_time = date("YmdHis", filemtime($_SERVER['DOCUMENT_ROOT'].$file_path));
	return "<link href=\"{$file_path}?ver={$file_time}\" type=\"text/css\" rel=\"stylesheet\"/>".PHP_EOL;
}

//Javascript 파일 로드 함수
function script_load($file_path) {
	//$file_path는 절대경로를 넣어준다

	$file_time = date("YmdHis", filemtime($_SERVER['DOCUMENT_ROOT'].$file_path));
	return "<script src=\"{$file_path}?ver={$file_time}\"></script>".PHP_EOL;
}


// 파라미터 클린 함수
function xss_clean($data)
{
	// data 빈값일 경우 return
	if(empty($data)) {
        return $data;
    }

    // array 형태일 경우 
    if(is_array($data)) {
        foreach($data as $key => $value) {
            $data[$key] = xss_clean($value);
        }

        return $data;
    }

    // Fix &entity\n;
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/i', '$1;', $data);

    if (function_exists("html_entity_decode")) {
        $data = html_entity_decode($data);
    } else {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        $data = strtr($data, $trans_tbl);
    }

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#i', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#i', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do
    {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    return $data;
}
