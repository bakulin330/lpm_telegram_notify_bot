<?php

//require_once 'SendMessage.php';
//require_once 'VerifyUser.php';

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.01.2016
 * Time: 16:05
 */
class Bot
{
    protected $verify;
    protected $send;

    public function __construct(){
        $this->verify = new VerifyUser();
        $this->send = new Telegram('156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4', 'https://wp.12qw.ru/telegram/index.php');
    }

    public function process($data)
    {
        if (isset($data['message']) && isset($data['message']['text'])){
            switch ($data['message']['text']){
                case '/start':
                    //������ ���������, ���  ����� �������� ����

                    break;

                case '/stop':
                    //������� �� chat_id �������� � ������������
                    break;

                default:
                    if (preg_match('#^\d+$#', $data['message']['text'])){
                        //�����
                        $this->connectUserByCode($data['message']['text'], $data['message']['chat']['id']);
                    } else {
                        //nothing
                    }
            }
        }
    }

    public function connectUserByCode($code,$chat_id)
    {
        $code = $this->verify->checkCode($code);
        if ($code) {
            $this->send->sendMessage('OK',$chat_id);
        } else {
            $this->send->sendMessage('FALSE',$chat_id);
        }
    }
}