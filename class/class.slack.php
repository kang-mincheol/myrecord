<?php

define('SLACK_URL_RECORD_INSERT', "https://hooks.slack.com/services/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");
define('SLACK_URL_ERROR', "https://hooks.slack.com/services/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");
define('SLACK_GIT_RELEASE', "https://hooks.slack.com/services/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");

class Slack {
    public static function send($url, $msg) {
        $params = $msg; // 보낼 메세지 내용
        return Slack::callApi($url, $params); 
    }

    public static function callApi($url, $msg) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>"{
            text: '{$msg}'
        }",
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if($response != "ok") {
            // Slack::send()
        }

        return true;
    }
}

?>