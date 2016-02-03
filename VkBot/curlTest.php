<?php
$url = "https://api.vk.com/method/messages.getDialogs";
$params = [
    'access_token' => "31e753f814e8b3dec1754e580aa7c1a9ef3020aa6e6606d307a71b271be99a5f4bce6e0c243bc65c13ba8",
    'v' => '5.44',
    'count' => 1,
    'unread' => 1,
];
$result = file_get_contents($url,false, stream_context_create(array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($params)
        ))));
preg_match("#https:\\\/\\\/m\.vk\.com\\\/login\?act=security_check&api_hash=(\w*)#",$result,$match);
//        var_dump($match);
$hash  = $match[1];
//$ch = curl_init("https://m.vk.com/login?act=security_check&api_hash=$hash");
$url = "https://m.vk.com/login?act=security_check&api_hash=$hash";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "act=security_check&api_hash=$hash");
//curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
//    'content-type: application/x-www-form-urlencoded',
//));
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

$content = curl_exec($ch);
curl_close($ch);
//htmlspecialchars($content);

preg_match("#login\.php\?act=security_check&to=&hash=(\w*)&api_hash=(\w*)#",$content,$url);
//var_dump($hashes);
//$hash = $hashes[1];
//$api_hash = $hashes[2];
$url  = "vk.com/$url[0]";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "code=95195209");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'content-type: application/x-www-form-urlencoded',
));
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$content = curl_exec($ch);
var_dump($content);
curl_close($ch);
//echo htmlspecialchars($content);