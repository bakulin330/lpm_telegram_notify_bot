<?php

namespace src;

require_once __DIR__.DIRECTORY_SEPARATOR.'Gen.php';

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.01.2016
 * Time: 14:44
 */
class VerifyUser
{
    public function checkCode($code){
        $gen = new Gen();
        $data = $gen->readDataFile();
        foreach ($data as $key => $user_id){
            if ($key == $code ){
                return $user_id;
            }
        }
        return false;
    }
}
