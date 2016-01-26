<?php

require_once '/../config.php';
require_once '/../classes/MailgunLetter.php';
require_once '/../classes/MailgunMailer.php';
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19.01.2016
 * Time: 17:29
 */
class MailgunLetterTest extends PHPUnit_Framework_TestCase
{

    protected $letter;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->letter = new MailgunLetter( new MailgunMailer('key','url'));
    }

    public function testTo()
    {
        $this->assertEquals('',$this->letter->getTo());
        $this->letter->to(123);
        $this->assertEquals(123,$this->letter->getTo());
    }

    public function testSubject()
    {
        $this->assertInstanceOf('MailgunLetter', $this->letter->subject('subj'));
        $this->assertEquals('subj', $this->letter->getSubject());
    }

    public function testPlain_message()
    {
        $this->assertInstanceOf('MailgunLetter', $this->letter->plain_message('message'));
        $this->assertEquals('message', $this->letter->getPlain_message());
    }

    public function testHtml_message()
    {
        $this->assertInstanceOf('MailgunLetter', $this->letter->html_message('html_message'));
        $this->assertEquals('html_message', $this->letter->getHtml_message());
    }

    public function testSetIsHtml()
    {
        $this->assertFalse($this->letter->getIsHtml());
        $this->assertInstanceOf('MailgunLetter', $this->letter->setIsHtml());

        $this->letter->setIsHtml(true);
        $this->assertTrue($this->letter->getIsHtml());
    }

    public function testSetLayout()
    {
        $this->assertInstanceOf('MailgunLetter', $this->letter->setLayout('layout'));
        $this->assertEquals('layout', $this->letter->getLayout());
    }

    public function testSetTemplate()
    {
        $this->assertInstanceOf('MailgunLetter', $this->letter->setTemplate('template'));
        $this->assertEquals('template', $this->letter->getTemplate());
    }

    public function testDrawTemplate()
    {
        $path = DIR_ROOT.'template'.DS.'test1.php';

//        file_put_contents($path,"Test template\nvalue = <?=\$this->variables['val'];");
        $this->letter->setVariables(['val'=>1234]);
        $this->assertEquals("Test template\nvalue = 1234",$this->letter->drawTemplate($path));
    }

    public function testDrawLayout()
    {
        $path = DIR_ROOT.'layouts'.DS.'unitTest.php';
        $this->letter->setLayout('test');
        $this->assertEquals('src="cid:img1.png"',$this->letter->drawLayout($path));
        $this->letter->is_preview();
        $this->assertEquals('src="img'.DS.'test'.DS.'img1.png"',$this->letter->drawLayout($path));
    }

    public function testDraw()
    {
        $this->letter->plain_message('321');
        $this->assertArrayHasKey('text', $this->letter->draw());
        $this->letter->plain_message('');
        $this->letter->html_message('<b>HELLO </b>');
        $this->assertArrayHasKey('html' ,$this->letter->draw());
    }

    public function testDraw2()
    {
        $this->letter->setVariables(["arr" => "test", "arr2" => "draw" ]);
        $this->letter->setIsPlain();
        $this->letter->setTemplate('test1');
        $this->letter->setLayout('test');
        $this->assertArrayHasKey('text',$this->letter->draw());
    }

    public function testGetLayoutImages()
    {
        $this->assertArrayHasKey('inline[0]', $this->letter->getLayoutImages('test'));
    }

    public function testSetVariables()
    {
        $this->letter->setVariables(['var' => 1]);
        $this->assertArraySubset(['var' => 1], $this->letter->getVariables());
    }

    public function testSend()
    {
        $this->letter->plain_message('test');
        $this->letter->is_preview();
        $this->assertNull($this->letter->send());
    }
}
