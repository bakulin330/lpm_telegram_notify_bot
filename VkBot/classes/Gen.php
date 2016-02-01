<?php
require 'DatabaseImitation.php';


class Gen
{
    protected $data_file;
    protected $db;

    public function __construct()
    {
        $this->db = new DatabaseImitation();
        $this->data_file = DIR_TMP.'vk_notify_code.php';
    }

    public function getUserCode($user_id)
    {
        $data = $this->db->readFileWithCodes();
        foreach ($data as $code => $user) {
            if ($user === $user_id) {
                return $code;
            }
        }
        return null;
    }

    public function generateCodeForUser($user_id)
    {
        if (null !== $this->getUserCode($user_id)) {
            throw new \Exception('user already has code', 1873);
        }

        $data = $this->readDataFile();
        $data[$this->generateCode()] = $user_id;
        $this->db->writeCode($data);
    }


    public function generateCode()
    {
        $data = $this->db->readFileWithCodes();
        $cnt = 0;

        while($cnt < 100){
            $code = mt_rand(1000,9999);
            if (!isset($data[$code])){
                return $code;
            }
            $cnt++;
        }

        throw new \Exception('cant\'t generate code', 1874);
    }


}