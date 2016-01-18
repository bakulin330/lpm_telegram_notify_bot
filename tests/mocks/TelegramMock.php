<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18.01.2016
 * Time: 10:11
 */
class TelegramMock implements \src\TelegramInterface
{
    public function apiRequestWebhook($method, $parameters){

    }

    public function sendMessage($message, $chat_id){
        $mes = $message;
        switch($mes){
            case 'not int':
                return 'not int';
            case 'Вы отключили функцию уведомления':
                return 'delete';
            case 'Введите сгенерированный код':
                return 'enter';
        }
    }

    public function sendWebhookMessage($message, $chat_id){
        $mes = $message;
        if ($mes === 'Неверный код') return false;
        else return true;
     }
}