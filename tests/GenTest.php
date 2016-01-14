<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.01.2016
 * Time: 9:17
 */
class GenTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \src\Gen
     */
    protected $gen;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.php';
        $this->gen = new \src\Gen();
    }

    public function testGetRandomNumber()
    {
        $v = $this->gen->getRandomNumber();
        $this->checkCode($v);
    }

    protected function checkCode($v)
    {
        $this->assertTrue(is_int($v));
        $this->assertTrue($v >= 1000);
        $this->assertTrue($v <= 9999);

        $v2 = $this->gen->getRandomNumber();
        $this->assertNotEquals($v, $v2);
    }

    public function testGetUserCode()
    {
        $this->gen->writeDataFile([
            3923 => 3,
        ]);

        $this->assertNull($this->gen->getUserCode(1));
        $this->assertNull($this->gen->getUserCode(2));
        $this->assertEquals(3923, $this->gen->getUserCode(3));
    }

    public function testCheckCode()
    {

    }

    public function testGenerateCode()
    {
        $user_id = 11;
        $this->assertNull($this->gen->getUserCode($user_id));

        $this->gen->generateCodeForUser($user_id);
        $v = $this->gen->getUserCode($user_id);
        $this->checkCode($v);

        //проверяем, что нельзя сгенерировать ключ снова, если он уже сгенерирован
        try {
            $this->gen->generateCodeForUser($user_id);
        } catch (Exception $e) {
            if ($e->getCode() === 1873) {
                //ok
            } else {
                throw $e;
            }
        }
    }
}