<?php
require_once 'config.php';
require DIR_CLASSES.'Bot.php';

$bot = new Bot();
$dialogs = $bot->getDialogsWithNewMessages();
if($dialogs){
    $code = $bot->readNewMessages($dialogs);
}

