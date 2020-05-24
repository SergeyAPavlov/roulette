<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use roulette\Model\Rules;

class RulesTest extends TestCase
{

    public function testOne()
    {
        $rules = new Rules();

        $check = $rules->checkField('one', 16, 16, 1);
        $this->assertEquals($check, 36);

        $check2 = $rules->checkField('one', 15, 16, 1);
        $this->assertEquals($check2, 0);
    }

    public function testNotOne()
    {
        $rules = new Rules();

        $check2 = $rules->checkField('one', 15, 16, 1);
        $this->assertEquals($check2, 0);
    }

    public function testTwo()
    {
        $rules = new Rules();

        $check = $rules->checkField('two', 16, 17, 1);
        $this->assertEquals($check, 18);
    }

    public function testThree()
    {
        $rules = new Rules();

        $check = $rules->checkField('three', 16, 17, 1);
        $this->assertEquals($check, 12);
        $check = $rules->checkField('three', 1, 2, 2);
        $this->assertEquals($check, 24);
    }

    public function testZero()
    {
        $rules = new Rules();

        $check = $rules->checkField('three', 16, 0, 1);
        $this->assertEquals($check, 0);
    }

    public function testZero2()
    {
        $rules = new Rules();

        $check = $rules->checkField('black', 0, 0, 10);
        $this->assertEquals($check, 0);
    }

    public function testBlack()
    {
        $rules = new Rules();

        $check = $rules->checkField('black', 16, 6, 1);
        $this->assertEquals($check, 0);

        $check = $rules->checkField('black', 17, 7, 1);
        $this->assertEquals($check, 2);

    }

    public function testRed()
    {
        $rules = new Rules();

        $check = $rules->checkField('red', 16, 7, 1);
        $this->assertEquals($check, 0);

        $check = $rules->checkField('red', 17, 6, 1);
        $this->assertEquals($check, 2);

    }

    /**
     * @expectedException \Exception
     */

    public function testWrongField()
    {
        $rules = new Rules();
        $check = $rules->checkField('two', 16, -1, 1);
    }

    /**
     * @expectedException \Exception
     */

    public function testWrongChoose()
    {
        $rules = new Rules();
        $check = $rules->checkField('two', 38, 2, 1);
    }

    /**
     * @expectedException \Exception
     */

    public function testWrongChooseZero()
    {
        $rules = new Rules();
        $check = $rules->checkField('two', 0, 2, 1);
    }

    /**
     * @expectedException \Exception
     */

    public function testWrongType()
    {
        $rules = new Rules();
        $check = $rules->checkField('error', 1, 2, 1);
    }

}