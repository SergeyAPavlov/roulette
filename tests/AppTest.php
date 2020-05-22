<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use roulette\App;

class AppTest extends TestCase
{
    protected $fixture;
    public $oldTurn;

    protected function setUp()
    {
        $this->fixture = new App();
        $store = \roulette\Service\ServiceProvider::getStore();
        $this->oldTurn = $store->getCurrentTurn();
        $store->setTurn(-11);

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

        $user1 = new \roulette\Model\User(-1);
        $user1->delete();
        $user2 = new \roulette\Model\User(-2);
        $user2->delete();
        $user3 = new \roulette\Model\User(-3);
        $user3->delete();
        \roulette\Model\Turn::clear(-11);
        $store = \roulette\Service\ServiceProvider::getStore();
        $store->setTurn($this->oldTurn);
        $this->fixture = NULL;

    }
    public function testReceiveBets()
    {
        /** @var App $app */
        $app = $this->fixture;
        $store = \roulette\Service\ServiceProvider::getStore();
        $this->receiveBets();
        $collect = $app->collect($store->getCurrentTurn());
        $firstBet = current($collect);
        $this->assertTrue(is_object($firstBet));
        $this->assertTrue($firstBet->sum == 12);
        \roulette\Model\Turn::clear(-11);
    }

    public function receiveBets()
    {
        /** @var App $app */
        $app = $this->fixture;
        $app->receiveBet(-1, 12, 'twelve', 5);
        $app->receiveBet(-2, 6, 'black');
        $app->receiveBet(-3, 3, 'one');
    }

    public function testTurn()
    {
        /** @var App $app */
        $app = $this->fixture;
        $this->receiveBets();
        $app->nextTurn();
        $turn = new \roulette\Model\Turn();
        $turn->load($this->oldTurn);

        $this->assertTrue(true);
        \roulette\Model\Turn::clear(-11);
    }


}