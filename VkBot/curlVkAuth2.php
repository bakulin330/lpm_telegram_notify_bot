<?php

$access_token = "31e753f814e8b3dec1754e580aa7c1a9ef3020aa6e6606d307a71b271be99a5f4bce6e0c243bc65c13ba8";
$v = '5.44';

$headers = array(
    'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'content-type' => 'application/x-www-form-urlencoded',
    'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36'
);

$get_new_access_token = post("https://api.vk.com/method/messages.getDialogs", array(
    'params' => 'access_token='.$access_token.'&v='.$v.'count=1&unread=1',
    'headers' => array(
        'accept: '.$headers['accept'],
        'content-type: '.$headers['content-type'],
        'user-agent: '.$headers['user-agent']
    )
));

var_dump($get_new_access_token);




function post($url = null, $params = null) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    if(isset($params['params'])) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params['params']);
    }

    if(isset($params['headers'])) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $params['headers']);
    }

    if(isset($params['cookies'])) {
        curl_setopt($ch, CURLOPT_COOKIE, $params['cookies']);
    }

    $result = curl_exec($ch);

    list($headers, $result) = explode("\r\n\r\n", $result, 4);

    preg_match_all('|Set-Cookie: (.*);|U', $headers, $parse_cookies);

    $cookies = implode(';', $parse_cookies[1]);

    curl_close($ch);

    return array('headers' => $headers, 'cookies' => $cookies, 'content' => $result);
}