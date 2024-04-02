<?php

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