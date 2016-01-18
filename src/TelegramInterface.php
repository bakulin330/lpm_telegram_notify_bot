<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18.01.2016
 * Time: 9:45
 */

namespace src;


interface TelegramInterface
{
    public function apiRequestWebhook($method, $parameters);
    public function sendMessage($message, $chat_id);
    public function sendWebhookMessage($message, $chat_id);
}