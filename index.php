<?php

require_once 'config.php';

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
     //receive wrong update, must not happen
   exit;
}

$send = new src\Telegram(API_KEY, WEBHOOK_URL);
$send->sendMessage('i can send message', 158922852);


if (isset($update["message"])) {
    $message = print_r($update, true);
    file_put_contents('log.txt', $message);
}

$bot = new \src\Bot(new \src\VerifyUser(), new \src\Telegram(API_KEY, WEBHOOK_URL));
$bot->process($update);




//if (isset($update["message"])) {
    //$message = print_r($update,true);
   // file_put_contents('log.txt', $message);
    //$telegram = new Telegram('156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4', 'https://wp.12qw.ru/telegram/index.php');
   // $telegram->processMessage($update["message"]);


//    $telegram = new Telegram('156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4', 'https://wp.12qw.ru/telegram/index.php');
//    $verify = new VerifyUser();
//    if($verify->checkCode($update["text"] == true)){
//        $telegram->sendMessage('OK', $update["chat"]["id"]);
//    }else{
//        $telegram->sendMessage('FALSE', $update["chat"]["id"]);
//    }


//}
