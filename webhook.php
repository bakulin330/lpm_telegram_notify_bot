<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12.01.2016
 * Time: 12:01
 */

require_once 'config.php';
$cert_file = '/etc/nginx/keys/tele.pem';

if (!isset($argv[1])){
    die('set first param to "on" or "off"');
}

$telegram = new \src\Telegram(API_KEY, WEBHOOK_URL);
echo $telegram->setWebhook('on'===$argv[1], $cert_file);