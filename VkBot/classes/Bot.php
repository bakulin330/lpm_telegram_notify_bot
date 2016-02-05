<?php
require 'DatabaseImitation.php';
class Bot
{
    protected $db;
    protected $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->db = new DatabaseImitation();
    }

    public function sendRequest($method, $par)
    {
        $req_method = $method;
        $url = $this->settings['url'].$req_method;

        $params = [
            'access_token' => $this->settings['access_token'],
            'v' => $this->settings['v'],
        ];

        $params += $par;

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

        $result = json_decode($result , true);
        if(isset($result['error']) && $result['error']['error_code'] == 9){
            $this->sendMessage($par['message'].'.',$par['user_id']);
        }

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
            'count' => '3',
            'unread' => '1',
            'preview_length' => '10',
        ];

        $result = $this->sendRequest("messages.getDialogs", $params);
        if(empty($result['response']['items'])) return false;
        $this->checkMessages($result['response']['items']);
    }


    public function checkMessages($items)
    {
        for($i = 0 ; $i < count($items); $i++) {
            $this->markMessageAsRead($items[$i]['message']['id']);

            if ("/stop" == $items[$i]['message']['body'] && $this->alreadyConnected($items[$i]['message']['user_id'])){
                $this->stopNotify($items[$i]['message']['user_id']);
            }
            elseif($this->alreadyConnected($items[$i]['message']['user_id'])){
                $this->sendMessage("Вам уже подключены уведомления. Для отключения введите /stop", $items[$i]['message']['user_id']);
            }
            elseif (preg_match("/^\d{".$this->settings['code_length']."}$/",$items[$i]['message']['body'])) {
                $this->connectUserByCode($items[$i]['message']['body'],$items[$i]['message']['user_id']);
            }
            else {
                $this->sendMessage("Код должен состоять из ".$this->settings['code_length']." цифр", $items[$i]['message']['user_id']);
            }
            usleep(350000);
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
//            if($this->isFriends($vk_user_id)) {
//                $this->sendMessage("Пожалуйста, отмените заявку в друзья", $vk_user_id);
//                return false;
//            }
            $data[$user_id] = $vk_user_id;
            $this->db->writeConnectedUser($data);
            $this->sendMessage("Вы успешно подключили функцию уведомления", $vk_user_id);
            return true;
        }else {
            $this->sendMessage("Неверный код", $vk_user_id);
            return false;
        }
    }

    public function isFriends($vk_user_id)
    {
        $params = [
            'user_ids' => $vk_user_id,
            'fields' => 'friend_status',
        ];

        $result = $this->sendRequest('users.get',$params);

        if ($result['response'][0]['friend_status'] !== 0 ) return true;
        else return false;
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