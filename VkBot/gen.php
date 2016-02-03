<?php
require_once 'config.php';
require DIR_CLASSES.'Gen.php';

$gen = new Gen();
$code = $gen->getUserCode($user_id);

if (null!==$code){
    echo 'Ваш код: '.$code.'<br/>Напишите его боту вконтакте';
} else {
    if (isset($_GET['generate'])){
        $gen->generateCodeForUser($user_id);
        header("Location: ".BASE_URL."gen.php");
    } else {
        echo 'У вас еще нет кода. <a href="'.BASE_URL.'gen.php?generate=1">Сгенерировать</a>';
    }
}

