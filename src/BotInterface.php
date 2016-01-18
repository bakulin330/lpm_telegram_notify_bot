<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18.01.2016
 * Time: 9:43
 */

namespace src;


interface BotInterface
{
    public function process($data);
    public function connectUserByCode($code,$chat_id);
    public function writeDataFile($data);
    public function readDataFile();
    public function deleteChatID($search_chat_id);
    public function alreadyConnected($data);
}