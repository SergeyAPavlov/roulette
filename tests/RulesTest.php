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

}