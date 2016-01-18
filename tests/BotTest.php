<?php

require_once '/mocks/TelegramMock.php';

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 15.01.2016
 * Time: 14:09
 */
class BotTest extends PHPUnit_Framework_TestCase
{
    protected $bot;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $mock = new TelegramMock();

        $this->bot = new \src\Bot(new \src\VerifyUser(), $mock);
    }



    public function testWriteDataFile()
    {
        $data = ['2' => 4];

        $this->assertInternalType('integer',$this->bot->writeDataFile($data));

    }

    public function testConnectUserByCode()
    {
        $chat = ['message' => ['chat' => ['id' => '007']]];
//        $falseCode = '0000';
        $trueCode = '3923';

//        $this->assertFalse($this->bot->connectUserByCode($falseCode,$chat));
        $this->assertTrue($this->bot->connectUserByCode($trueCode,$chat));

    }

    public function testAlreadyConnected()
    {
        $need = ['message' => ['chat' => ['id' => 4]]];
        $need2 = ['message' => ['chat' => ['id' => 1]]];

        $this->assertTrue($this->bot->alreadyConnected($need));
        $this->assertFalse($this->bot->alreadyConnected($need2));
    }

    public function testDeleteChatID()
    {
        $this->bot->writeDataFile([
            22 => 23204
        ]);
        $this->assertFalse($this->bot->deleteChatID(222));
        $this->assertTrue($this->bot->deleteChatID(23204));
    }


    public function testProcess()
    {
        $this->bot->writeDataFile([
            '2' => 4,
        ]);
        $notInt = ['message' => ['chat' => ['id' => 5], 'text' => 'hi']];
        $stop = ['message' => ['chat' => ['id' => 4], 'text' => '/stop']];
        $connect = ['message' => ['chat' => ['id' => 4], 'text' => '0000']];
        $enter = ['message' => ['chat' => ['id' => 4], 'text' => '/start']];

        $this->assertEquals('not int', $this->bot->process($notInt));
        $this->assertEquals('delete', $this->bot->process($stop));
        $this->assertEquals('no_cmd', $this->bot->process(['message' => ['chat' => ['id' => 49999], 'text' => '/stop']]));
        $this->assertFalse( $this->bot->process($connect));
        $this->assertEquals('enter', $this->bot->process($enter));
    }

    public static function tearDownAfterClass () {
        $data = ['1' => 3];
        file_put_contents(DIR_TMP . 'telegram_notify_chat.php', "<?php return " . var_export($data, true) . ";", EXTR_OVERWRITE);
    }




}
