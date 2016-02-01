<?php
require 'DatabaseImitation.php';
class Bot
{
    protected $db;
    protected $url;
    protected $vk_user_id;
    protected $bot_id;
    protected $access_token;
    protected $message;
    protected $v = "5.44";

    public function __construct()
    {
        $this->db = new DatabaseImitation();
        $this->url = 'https://api.vk.com/method/';
        $this->access_token = "9f9a7a16f4b31d712d1df6d7e9b0de63f7fa102b794b4be8ab3c8d61962b0988e05b8f5e7b741019a0257";
        $this->bot_id = 16309784;
    }

    public function sendRequest($method, $par)
    {
        $req_method = $method;
        $url = $this->url.$req_method;
        $params = array(
            'access_token' => $this->access_token,
            'v' => $this->v,
        );

        $params += $par;

//        echo 'params: '.vd( $params ).'<br/>';
//        $opt = "count=1&unread=1&preview_length=10&access_token=$this->access_token&v=$this->v";

//        $handle = curl_init($url);
//        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
//        curl_setopt($handle, CURLOPT_POST, true);
//        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
//        curl_setopt($handle, CURLOPT_POSTFIELDS, $params);
//        curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded'"));
//        curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
//        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
//        $result = curl_exec($handle);
//        $code = curl_getinfo($handle,CURLINFO_HTTP_CODE );
//
//        echo 'status: '.vd($code).'<br/>';
//        echo 'result: '.vd($result).'<br/>';

        $result = file_get_contents($url, false, stream_context_create(array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));

        return $result;
    }

    public function sendMessage($message, $vk_user_id)
    {
        $params = [
            'message' => $message,
            'user_id' => $vk_user_id,
        ];

        $this->sendRequest("messages.send",$params);
    }

    public function getDialogsWithNewMessages()
    {
        $params = [
            'count' => '1',
            'unread' => '1',
            'preview_length' => '10',
        ];

        $result = $this->sendRequest("messages.getDialogs", $params);
        $result = json_decode($result,true);
        //var_dump($result);
        if($result['response']['items']) $this->checkMessages($result['response']['items']);
    }


    public function checkMessages($items)
    {
        for($i = 0 ; $i < count($items); $i++) {
            $this->markMessageAsRead($items[$i]['message']['id']);

            if (preg_match("/^\/stop$/", $items[$i]['message']['body']) && $this->alreadyConnected($items[$i]['message']['user_id'])){
                $this->stopNotify($items[$i]['message']['user_id']);
            }
            elseif($this->alreadyConnected($items[$i]['message']['user_id'])){
                $this->sendMessage("Для отключения функции уведомления введите команду : /stop ", $items[$i]['message']['user_id']);
            }
            elseif (preg_match("/^\d{4}$/", $items[$i]['message']['body'])) {
                $this->connectUserByCode($items[$i]['message']['body'],$items[$i]['message']['user_id']);
            }
            else {
                $this->sendMessage("Код должен состоять из четырех цифр", $items[$i]['message']['user_id']);
            }
        }
    }

    public function stopNotify($vk_user_id)
    {
        $data = $this->db->readConnectedUsersFile();
        foreach ($data as $user_id => $connected_vk_id){
            if ($connected_vk_id === $vk_user_id){
                unset($data[$user_id]);
                $this->db->writeConnectedUser($data);
                $this->sendMessage("Функция уведомления о заказах отключена. Для повторного подключения введите ранее сгенерированный код. ",$vk_user_id);
                return true;
            }
        }
        $this->sendMessage("Код должен состоять из четырех цифр", $vk_user_id);
        return false;
    }

    public function connectUserByCode($code, $vk_user_id)
    {
        $user_id = $this->isExistUser($code);
        if ($user_id) {
            $status = $this->checkFriendStatus($vk_user_id);
            if($status !== 0){
                $this->sendMessage("В целях сохранения Вашей конфиденциальности, пожалуйста, отмените заявку ко мне в друзья", $vk_user_id);
                return false;
            }
            $data[$user_id] = $vk_user_id;
            $this->db->writeConnectedUser($data);
            $this->sendMessage("Вы успешно подключили функцию уведомления", $vk_user_id);
            return true;
        }else {
            $this->sendMessage("Неверный код", $vk_user_id);
            return false;
        }
    }

    public function checkFriendStatus($vk_user_id)
    {
        $params = [
            'user_ids' => $vk_user_id,
            'fields' => 'friend_status',
        ];

        $result = $this->sendRequest('users.get',$params);
        $result = json_decode($result,true);

        return $result['response'][0]['friend_status'];
    }

    public function alreadyConnected($vk_user_id)
    {
        $data = $this->db->readConnectedUsersFile();
        foreach ($data as $user_id => $connected_vk_id){
            if($connected_vk_id === $vk_user_id){
                return true;
            }
        }
        return false;
    }
    
    
    public function markMessageAsRead($message_id)
    {
        $params = [
            'message_ids' => $message_id,
        ];

        $this->sendRequest("messages.markAsRead", $params);
        return;
    }

    

    public function isExistUser($code)
    {
        $data = $this->db->readFileWithCodes();
        foreach ($data as $key => $user_id){
            if ($key == $code ){
                return $user_id;
            }
        }
        return false;
    }
}