<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가










//숫자인지 확인하는 함수
function is_number($var){
    if ($var == (string) (float) $var) {
        return (bool) is_numeric($var);
    }
    if ($var >= 0 && is_string($var) && !is_float($var)) {
        return (bool) ctype_digit($var);
    }
    return (bool) is_numeric($var);
}


?>