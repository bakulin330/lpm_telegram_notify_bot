<?php
require 'DatabaseImitation.php';
class Bot
{
    protected $db;
    protected $url = 'https://api.vk.com/method/';
    protected $vk_user_id;
    protected $access_token = "31e753f814e8b3dec1754e580aa7c1a9ef3020aa6e6606d307a71b271be99a5f4bce6e0c243bc65c13ba8";
    protected $message;
    protected $v = "5.44";

    public function __construct()
    {
        $this->db = new DatabaseImitation();
    }

    public function sendRequest($method, $par)
    {
        $req_method = $method;
        $url = $this->url.$req_method;

        $params = [
            'access_token' => $this->access_token,
            'v' => $this->v,
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

        preg_match("#https:\\\/\\\/m\.vk\.com\\\/login\?act=security_check&api_hash=(\w*)#",$result,$match);
        var_dump($match);
        $hash  = $match[0][1];
        $ch = curl_init("https://m.vk.com/login?act=security_check&api_hash=$hash");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'content-type: application/x-www-form-urlencoded',
            'origin: http://vk.com',
            'referer: http://vk.com/',
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $content = curl_exec($ch);
        curl_close($ch);
        var_dump($content);


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
        $result = json_decode($result, true);
        if(empty($result['response']['items'])) return false;
        $this->checkMessages($result['response']['items']);
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