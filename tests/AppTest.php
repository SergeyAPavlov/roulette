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
        \roulette\Model\Turn::clear(-11);

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
        $store->delete(\roulette\Model\Turn::DATATYPE, -11);
        $this->fixture = NULL;

    }
    public function testReceiveBets()
    {
        /** @var App $app */
        $app = $this->fixture;
        $store = \roulette\Service\ServiceProvider::getStore();
        $this->receiveBets();
        $bets = [-1=>12, -2=>6, -3=>3];
        $collect = $app->collect($store->getCurrentTurn());
        $firstBet = current($collect);
        $this->assertTrue(is_object($firstBet));

        foreach ($collect as $key=>$item) {
            $this->assertTrue($item->sum == $bets[$item->userId]);
        }

        \roulette\Model\Turn::clear(-11);
    }

    public function receiveBets()
    {
        /** @var App $app */
        $app = $this->fixture;
        $app->receiveBet(-1, 12, 'twelve', 5);
        $app->receiveBet(-2, 6, 'black');
        $app->receiveBet(-3, 3, 'one', 4);
    }

    public function testTurn()
    {
        /** @var App $app */
        $app = $this->fixture;
        $this->receiveBets();
        $app->nextTurn(4);
        $turn = new \roulette\Model\Turn();
        $turn->load(-11);

        foreach ($turn->wins as $win) {
            if ($win->userId == -1) $this->assertEquals(0, $win->sum);
            if ($win->userId == -2) $this->assertEquals(0, $win->sum);
            if ($win->userId == -3) $this->assertEquals(108, $win->sum);
        }

        \roulette\Model\Turn::clear(-11);
    }

    public function testTurn2()
    {
        /** @var App $app */
        $app = $this->fixture;
        $this->receiveBets();
        $app->nextTurn(10);
        $turn = new \roulette\Model\Turn();
        $turn->load(-11);

        foreach ($turn->wins as $win) {
            if ($win->userId == -1) $this->assertEquals(36, $win->sum);
            if ($win->userId == -2) $this->assertEquals(0, $win->sum);
            if ($win->userId == -3) $this->assertEquals(0, $win->sum);
        }

        \roulette\Model\Turn::clear(-11);
    }


}