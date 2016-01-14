<?php

namespace src;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.01.2016
 * Time: 16:05
 */
class Bot
{
    /**
     * @var VerifyUser
     */
    protected $verify;

    /**
     * @var Telegram
     */
    protected $telegram;

    public function __construct($verifier, $telegram){
        $this->verify = $verifier;
        $this->telegram = $telegram;
    }

    public function process($data)
    {
        if (isset($data['message']) && isset($data['message']['text'])){
            switch ($data['message']['text']){
                case '/start':
                    //выодим сообщение, что  нужно написать ключ

                    break;

                case '/stop':
                    //удаляем по chat_id привязку к пользователю
                    break;

                default:
                    if (preg_match('#^\d+$#', $data['message']['text'])){
                        //число
                        $this->connectUserByCode($data['message']['text'], $data['message']['chat']['id']);
                    } else {
                        $this->telegram->sendMessage('not int',$data['message']['chat']['id']);
                    }
            }
        }
    }

    public function connectUserByCode($code,$chat_id)
    {
        $code = $this->verify->checkCode($code);
        if ($code) {
            $this->telegram->sendMessage('OK',$chat_id);
        } else {
            $this->telegram->sendMessage('FALSE',$chat_id);
        }
    }
}