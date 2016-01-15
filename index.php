<?php

$content = file_get_contents("php://input");
$update = json_decode($content, true);

//if (!$update) {
     //receive wrong update, must not happen
//    exit;
//}

require_once 'config.php';

if (isset($update["message"])){
    $bot = new \src\Bot(new \src\VerifyUser(), new \src\Telegram(API_KEY, WEBHOOK_URL));
    $message = print_r($update, true);
    file_put_contents('log.txt', $message);
    $bot->process($update);

}
