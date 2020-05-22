<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use roulette\App;

class AppTest extends TestCase
{
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new App();
        $user1 = new \roulette\Model\User(-1);
        $user1->name = 'user1';
        $user1->accountSum = 100;
        $user1->save();

        $user2 = new \roulette\Model\User(-2);
        $user2->name = 'user2';
        $user2->accountSum = 200;
        $user2->save();

        $user3 = new \roulette\Model\User(-3);
        $user3->name = 'user3';
        $user3->accountSum = 300;
        $user3->save();

    }

    protected function tearDown()
    {
        $this->fixture = NULL;
        $user1 = new \roulette\Model\User(-1);
        $user1->delete();
        $user2 = new \roulette\Model\User(-2);
        $user2->delete();
        $user3 = new \roulette\Model\User(-3);
        $user3->delete();

    }
    public function testReceiveBets()
    {
        $app = $this->fixture;

    }
}