<?php namespace frontend\tests\components;

use frontend\components\GiftSender;

class GiftSenderTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;
    const indexK = 3;

    protected function _before()
    {

    }

    protected function _after()
    {
    }
    /**
     * Generated from @assert (100) == 300.
     */
    public function testConvertMoneyToBonuses()
    {
      //  return static::indexK*$money;
        $this->assertEquals(300, GiftSender::convertMoneyToBonuses(100) );
        $this->assertEquals(600, GiftSender::convertMoneyToBonuses("200") );
    }
}