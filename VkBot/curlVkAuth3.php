<?php

$login = '';
$password = '';
$security_check_code = '';

$headers = array(
    'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'content-type' => 'application/x-www-form-urlencoded',
    'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36'
);


$get_main_page = post('https://vk.com', array(
    'headers' => array(
        'accept: '.$headers['accept'],
        'content-type: '.$headers['content-type'],
        'user-agent: '.$headers['user-agent']
    )
));

preg_match('/name=\"ip_h\" value=\"(.*?)\"/s', $get_main_page['content'], $ip_h);
preg_match('/name=\"lg_h\" value=\"(.*?)\"/s', $get_main_page['content'], $lg_h);

$post_auth = post('https://login.vk.com/?act=login', array(
    'params' => 'act=login&role=al_frame&_origin='.urlencode('http://vk.com').'&ip_h='.$ip_h[1].'&lg_h='.$lg_h[1].'&email='.urlencode($login).'&pass='.urlencode($password),
    'headers' => array(
        'accept: '.$headers['accept'],
        'content-type: '.$headers['content-type'],
        'user-agent: '.$headers['user-agent']
    ),
    'cookies' => $get_main_page['cookies']
));

preg_match('/Location\: (.*)/s', $post_auth['headers'], $post_auth_location);

if(!preg_match('/\_\_q\_hash=/s', $post_auth_location[1])) {
    echo 'Не удалось авторизоваться <br /> <br />'.$post_auth['headers'];

    exit;
}

$get_auth_location = post($post_auth_location[1], array(
    'headers' => array(
        'accept: '.$headers['accept'],
        'content-type: '.$headers['content-type'],
        'user-agent: '.$headers['user-agent']
    ),
    'cookies' => $post_auth['cookies']
));

preg_match('/"uid"\:"([0-9]+)"/s', $get_auth_location['content'], $my_page_id);

$my_page_id = $my_page_id[1];

$get_my_page = getUserPage($my_page_id, $get_auth_location['cookies']);

if(preg_match('/act=security\_check/s', $get_my_page['headers'])) {
    preg_match('/Location\: (.*)/s', $get_my_page['headers'], $security_check_location);

    $get_security_check_page = post('https://vk.com'.$security_check_location[1], array(
        'headers' => array(
            'accept: '.$headers['accept'],
            'content-type: '.$headers['content-type'],
            'user-agent: '.$headers['user-agent']
        ),
        'cookies' => $get_auth_location['cookies']
    ));

    preg_match('/hash: \'(.*?)\'/s', $get_security_check_page['content'], $get_security_check_page_hash);

    $post_security_check_code = post('https://vk.com/login.php', array(
        'params' => 'act=security_check&code='.$security_check_code.'&al_page=2&hash='.$get_security_check_page_hash[1],
        'headers' => array(
            'accept: '.$headers['accept'],
            'content-type: '.$headers['content-type'],
            'user-agent: '.$headers['user-agent']
        ),
        'cookies' => $get_auth_location['cookies']
    ));

    echo 'Запрошена проверка безопасности';

    $get_my_page = getUserPage($my_page_id, $get_auth_location['cookies']);

    echo iconv('windows-1251', 'utf-8', $get_my_page['content']);
} else {
    echo iconv('windows-1251', 'utf-8', $get_my_page['content']);
}

function getUserPage($id = null, $cookies = null) {
    global $headers;

    $get = post('https://vk.com/id'.$id, array(
        'headers' => array(
            'accept: '.$headers['accept'],
            'content-type: '.$headers['content-type'],
            'user-agent: '.$headers['user-agent']
        ),
        'cookies' => $cookies
    ));

    return $get;
}

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
?>