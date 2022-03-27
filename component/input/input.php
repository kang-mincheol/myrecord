<?
//if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

include_once($_SERVER['DOCUMENT_ROOT']."/common.php");
/**************************************************
example 파일
**************************************************/
?>

<?
echo css_load("/component/input/input.css");
echo css_load("/fonts/fonts.css");
?>

<div style="width: 400px; margin: 0 auto">


<div class="myrecord_input_wrap form_wrap">
    <div class="label_box">
        <label class="wrap_label" for="test_input">입력</label>
    </div>
    <div class="form_value_box">
        <input id="test_input" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text" placeholder="입력"/>
    </div>
</div>

<div class="myrecord_input_wrap form_wrap">
    <div class="label_box">
        <label class="wrap_label" for="test_input_1">입력</label>
    </div>
    <div class="form_value_box">
        <input id="test_input_1" class="input_text alert" onkeyup="inputOnkeyupEvent(this);" type="text" placeholder="입력"/>
        <p class="caution_text">알림 텍스트</p>
    </div>
</div>

<p>체크박스</p>
<div class="myrecord_checkbox_wrap">
    <input class="myrecord_checkbox" type="checkbox"/>
    <p class="checkbox_bg"></p>
</div>


<div class="myrecord_input_wrap email_input">
    <div class="label_box">
        <label class="wrap_label" for="join_id">이메일 ID</label>
    </div>
    <div class="form_value_box">
        <input id="join_id" class="input_text" type="text" onkeyup="emailKeyEvent(this);" onchange="emailKeyEvent(this);" placeholder="가입 후 ID 변경 불가" autocomplete="off"/>
        <div class="email_list_box">
            <button class="email_list_btn" value="naver.com" onclick="emailListClick(this); joinStep1Activation();"></button>
            <button class="email_list_btn" value="gmail.com" onclick="emailListClick(this); joinStep1Activation();"></button>
            <button class="email_list_btn" value="kakao.com" onclick="emailListClick(this); joinStep1Activation();"></button>
            <button class="email_list_btn" value="daum.net" onclick="emailListClick(this); joinStep1Activation();"></button>
            <button class="email_list_btn" value="nate.com" onclick="emailListClick(this); joinStep1Activation();"></button>
            <button class="email_list_btn" value="hanmail.net" onclick="emailListClick(this); joinStep1Activation();"></button>
        </div>
    </div>
</div>


<div class="myrecord_input_wrap">
    <div class="label_box">
        <label class="wrap_label">셀렉트박스</label>
    </div>
    <div class="myrecord_select_wrap">
        <button class="select_remote_btn" value="none" onclick="selectListRemote(this);">선택하세요</button>
        <div class="select_list_wrap">
            <button class="select_list_btn" value="none" onclick="optionClick(this);">선택하세요</button>
            <button class="select_list_btn" value="1" onclick="optionClick(this);">선택1</button>
            <button class="select_list_btn" value="2" onclick="optionClick(this);">선택2</button>
        </div>
        <div class="mobile_select_wrap">
            <select class="mobile_select" onchange="mobileOptionClick(this);">
                <option value="none">선택하세요</option>
                <option value="1">선택1</option>
                <option value="2">선택2</option>
            </select>
        </div>
    </div>
</div>
<br/><br/><br/><br/><br/>


<div class="campusloan_wrap">
    
    <div class="myrecord_input_wrap form_wrap" name="pw_wrap">
        <div class="label_box">
            <label class="wrap_label" for="test_input">입력</label>
        </div>
        <div class="form_value_box">
            <input id="test_input" class="input_text" type="text" placeholder="입력"/>
        </div>
    </div>

    <p>체크박스</p>
    <div class="myrecord_checkbox_wrap">
        <input class="myrecord_checkbox" type="checkbox"/>
        <p class="checkbox_bg"></p>
    </div>


    <div class="myrecord_input_wrap email_input">
        <div class="label_box">
            <label class="wrap_label" for="join_id">이메일 ID</label>
        </div>
        <div class="form_value_box">
            <input id="join_id" class="input_text" type="text" onkeyup="emailKeyEvent(this);" onchange="emailKeyEvent(this);" placeholder="가입 후 ID 변경 불가" autocomplete="off"/>
            <div class="email_list_box">
                <button class="email_list_btn" value="naver.com" onclick="emailListClick(this); joinStep1Activation();"></button>
                <button class="email_list_btn" value="gmail.com" onclick="emailListClick(this); joinStep1Activation();"></button>
                <button class="email_list_btn" value="kakao.com" onclick="emailListClick(this); joinStep1Activation();"></button>
                <button class="email_list_btn" value="daum.net" onclick="emailListClick(this); joinStep1Activation();"></button>
                <button class="email_list_btn" value="nate.com" onclick="emailListClick(this); joinStep1Activation();"></button>
                <button class="email_list_btn" value="hanmail.net" onclick="emailListClick(this); joinStep1Activation();"></button>
            </div>
        </div>
    </div>


    <div class="myrecord_input_wrap">
        <div class="label_box">
            <label class="wrap_label">셀렉트박스</label>
        </div>
        <div class="myrecord_select_wrap">
            <button class="select_remote_btn" value="none" onclick="selectListRemote(this);">선택하세요</button>
            <div class="select_list_wrap">
                <button class="select_list_btn" value="none" onclick="optionClick(this);">선택하세요</button>
                <button class="select_list_btn" value="1" onclick="optionClick(this);">선택1</button>
                <button class="select_list_btn" value="2" onclick="optionClick(this);">선택2</button>
            </div>
            <div class="mobile_select_wrap">
                <select class="mobile_select" onchange="mobileOptionClick(this);">
                    <option value="none">선택하세요</option>
                    <option value="1">선택1</option>
                    <option value="2">선택2</option>
                </select>
            </div>
        </div>
    </div>
    
</div>





</div><!-- wrap -->

<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<?=script_load("/component/input/input.js");?>
<script>
	//셀렉트박스 사용시 아래함수를 호출할것
    selectDeviceCheck();
</script>