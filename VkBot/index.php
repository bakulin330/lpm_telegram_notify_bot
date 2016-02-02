<?php
require_once 'config.php';
require DIR_CLASSES.'Bot.php';

//function vd($v){
//    ob_start();
//    var_dump($v);
//    return '<pre>'.ob_get_clean().'</pre>';
//}

$bot = new Bot();
$dialogs = $bot->getDialogsWithNewMessages();
