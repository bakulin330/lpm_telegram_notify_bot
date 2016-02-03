<?php
$client_id = '5248311';
$scope = 'friends,offline,messages';
$acc_token = trim("cf61bb5d146bd7a0c7a1ecbf68e42f5b6026575bf8fe0c8f30ba99f4383815bcd6e676bd358355aba4370");
?>

<a href="https://oauth.vk.com/authorize?client_id=<?=$client_id;?>&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=<?=$scope;?>&response_type=token&v=5.37">Push the button</a>
<br>
<a href="https://api.vk.com/method/messages.send?user_id=16309784&message=dretgwe&v=5.44&access_token=bc78db5d2ef1a928ddfc4a06b2a6dde447852c236464c9710e62419ed55dd07b4e4778f19d019bf334039">Send message</a>
<br>
<a href="https://api.vk.com/method/friends.areFriends?user_ids=279739846&need_sign=0&v=5.44&access_token=110c02c7190ca3a3f0aa933525273a456a74b723bec1800cb17acb85d28ffc04e2a5d5b6d19fa526fdb01">check friend</a>
<br>
<a href="https://api.vk.com/method/messages.getDialogs?count=1&unread=1&preview_length=5&v=5.44&access_token=<?=$acc_token?>">check unreaded message</a>
<br>
<a href="https://api.vk.com/method/messages.getHistory?count=3&user_id=16309784&v=5.44&access_token=<?=$acc_token?>">get history</a>
<br>
<a href="https://api.vk.com/method/messages.getById?message_ids=167420&preview_length=5&v=5.44&access_token=<?=$acc_token?>">get message by  id's</a>
<br>
<a href="https://api.vk.com/method/users.get?user_ids=37561766&fields=friend_status&v=5.44&access_token=<?=$acc_token?>">check friend status</a>
<br>
<?php
//print_r(openssl_get_cert_locations());
$url  = "https://m.vk.com/login?act=security_check&api_hash=53762491b86b72a444";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'content-type: application/x-www-form-urlencoded',
    'origin: http://vk.com',
    'referer: http://vk.com/',
));
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

$content = curl_exec($ch);
var_dump($content);
