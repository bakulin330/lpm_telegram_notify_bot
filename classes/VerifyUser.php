<?php
require_once 'Gen.php';

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.01.2016
 * Time: 14:44
 */
class VerifyUser
{
    public function checkCode($code){
        $data = new Gen();
        $data = $data->readDataFile();

        foreach ($data as $key){
            if ($code === $key){
                return true;
            }else return false;
        }
    }


}