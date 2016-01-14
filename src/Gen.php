<?php
namespace src;
//echo __DIR__;
/**
 * класс для генерации кода активации
 */
class Gen
{
    const DATA_FILE = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'telegram_notify_code.php';

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
        return file_exists(self::DATA_FILE) ? include self::DATA_FILE : [];
    }

    public function writeDataFile($data)
    {
        return file_put_contents(self::DATA_FILE, "<?php return " . var_export($data, true) . ";", EXTR_OVERWRITE);
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
