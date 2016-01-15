<?php
namespace src;

/**
 * класс для генерации кода активации
 */
class Gen
{
    private $data_file;

    function __construct()
    {
        $this->data_file =  DIR_TMP.'telegram_notify_code.php';
        $this->data_file_chat =  DIR_TMP.'telegram_notify_chat.php';
    }

    public function getUserChatId($user_id)
    {
        $data = $this->readDataFileChat();
        foreach ($data as $user => $chat_id) {
            if ($user === $user_id) {
                return $chat_id;
            }
        }
        return null;
    }

    public function getUserCode($user_id)
    {
        $data = $this->readDataFile();
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
        $data[$this->getRandomNumber()] = $user_id;
        $this->writeDataFile($data);
    }

    public function readDataFile()
    {
        return file_exists($this->data_file) ? include $this->data_file : [];
    }

    public function readDataFileChat()
    {
        return file_exists($this->data_file_chat) ? include $this->data_file_chat : [];
    }

    public function writeDataFile($data)
    {
        return file_put_contents($this->data_file, "<?php return " . var_export($data, true) . ";", EXTR_OVERWRITE);
    }

    public function getRandomNumber()
    {
        $data = $this->readDataFile();
        $cnt = 0;

        while ($cnt < 100) {
            $code = mt_rand(1000, 9999);
            if (!isset($data[$code])) {
                return $code;
            }
            $cnt++;
        }

        throw new \Exception('cant\'t generate code', 1874);
    }
}
