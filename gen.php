<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.01.2016
 * Time: 9:31
 */

require_once 'config.php';
require DIR_CLASSES.'Gen.php';

$gen = new Gen();
$code = $gen->getUserCode($user_id);

if (null!==$code){
    //рисуем страницу с кодом
    echo 'Ваш код: '.$code.'<br/>Напишите его телеграм-боту';
} else {
    if (isset($_GET['generate'])){
        $gen->generateCodeForUser($user_id);
        header("Location: ".BASE_URL."gen.php");
    } else {
        //рисуем страницу с генерацией кода
        echo 'У вас еще нет кода. <a href="'.BASE_URL.'gen.php?generate=1">Сгенерировать</a>';
    }
}
