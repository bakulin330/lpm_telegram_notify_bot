<?php
require_once 'config.php';
require DIR_CLASSES.'Bot.php';
//require DIR_CLASSES.'Gen.php';
//require DIR_CLASSES.'DatabaseImitation.php';
?>

<form action = "" method="post">
    <textarea name="text" ></textarea>
    <input type="submit" name="send" value="Отправить">
</form>


<?php

if(isset($_POST["send"]) && $_POST["text"] !== ""){
    $user_message = $_POST["text"];
    $message = "Вам пришёл заказ от пользователя! Сообщение:\n".$user_message;

    $db = new DatabaseImitation();
    $vk_user_id = $db->getUserVkId($user_id);

    if ($vk_user_id){
        $bot = new Bot();
        $bot->sendMessage($message,$vk_user_id);
    }else {
        echo 'Вы не подключили telegram. <a href="'.BASE_URL.'gen.php">Подключить</a>.';
    }
}else {
    echo 'Сообщение не может быть пустым!';
}

?>

