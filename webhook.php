<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12.01.2016
 * Time: 12:01
 */

define('DIR_TMP', dirname(__FILE__).DIRECTORY_SEPARATOR);
$cert_file = '/etc/nginx/keys/tele.pem';

if (!isset($argv[1])){
    die('set first param to "on" or "off"');
}

require dirname(__FILE__).DIRECTORY_SEPARATOR.'Telegram.php';

$telegram = new Telegram('156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4', 'https://wp.12qw.ru/telegram/index.php');
echo $telegram->setWebhook('on'===$argv[1], $cert_file);