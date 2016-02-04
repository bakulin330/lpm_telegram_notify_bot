<?php

class DatabaseImitation
{
    protected $code_file;
    protected $connected_users;

    public function __construct()
    {
        $this->code_file = DIR_TMP.'vk_notify_code.php';
        $this->connected_users = DIR_TMP.'vk_connected_users.php';
    }

    public function writeCode($data)
    {
        return file_put_contents($this->code_file, "<?php return ". var_export($data,true) . ";", EXTR_OVERWRITE);
    }

    public function readFileWithCodes()
    {
        return file_exists($this->code_file) ? include $this->code_file : [];
    }

    public function writeConnectedUser($data)
    {
        $res = file_put_contents($this->connected_users, "<?php return ". var_export($data,true) . ";", EXTR_OVERWRITE);
        var_dump($res);
    }

    public function readConnectedUsersFile()
    {
        return file_exists($this->connected_users) ? include $this->connected_users : [];
    }

    public function getUserVkId($user_id)
    {
        $data = $this->readConnectedUsersFile();
        foreach ($data as $user => $vk_user_id) {
            if ($user === $user_id) {
                return $vk_user_id;
            }
        }
        return null;
    }
}