<?php
require_once 'config.php';
require DIR_CLASSES.'Bot.php';

$bot = new Bot($settings);
$dialogs = $bot->getDialogsWithNewMessages();
