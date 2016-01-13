<?php
require_once 'config.php';
require_once DIR_CLASSES.'Gen.php';
require_once DIR_CLASSES.'SendMessage.php';
?>

<form action = "" method="post">
    <textarea name="text" ></textarea>
    <input type="submit" name="send" value="Отправить">
</form>

<?php

if(isset($_POST["send"]) && $_POST["text"] !== ""){
    $user_message = $_POST["text"];
    $message = "Вам пришёл заказ от пользователя с ID: *".$user_id."*.\nСообщение: _".$user_message."_";

    $sender->sendMessage($user_id, $message);

    $gen = new Gen();
    $data = $gen->getUserCode($user_id);
    if ($data){
        $bot = new Telegram('156771533:AAFtGPT_o3MFuPRBnuYwOZGfNHWt_FivTy4', 'https://wp.12qw.ru/telegram/index.php');
        $bot->sendMessage($message,158922852);
    }else {
        echo 'Вы не подключили telegram. <a href="'.BASE_URL.'gen.php">Подключить</a>.';
    }
}else {
    echo 'Сообщение не может быть пустым!';
}

?>