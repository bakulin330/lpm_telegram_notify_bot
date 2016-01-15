<?php
require_once 'config.php';
?>

<form action = "" method="post">
    <textarea name="text" ></textarea>
    <input type="submit" name="send" value="Отправить">
</form>

<?php

if(isset($_POST["send"]) && $_POST["text"] !== ""){
    $user_message = $_POST["text"];
    $message = "Вам пришёл заказ от пользователя! \nСообщение: _".$user_message."_";


    $gen = new \src\Gen();
    $chat_id = $gen->getUserChatId($user_id);
    if ($chat_id){
        $bot = new \src\Telegram(API_KEY, BASE_URL);
        $bot->sendMessage($message,$chat_id);
    }else {
        echo 'Вы не подключили telegram. <a href="'.BASE_URL.'gen.php">Подключить</a>.';
    }
}else {
    echo 'Сообщение не может быть пустым!';
}

?>