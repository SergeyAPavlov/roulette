<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use roulette\App;
use roulette\Service\ServiceProvider;
use roulette\Model\User;
use roulette\Model\Turn;

class AppTest extends TestCase
{
    protected $fixture;
    public $oldTurn;

    protected function setUp()
    {
        $this->fixture = new App();

        ServiceProvider::setDevStores();
        $store = ServiceProvider::getStore();
        $this->oldTurn = $store->getCurrentTurn();
        $store->setTurn(-11);
        Turn::clear(-11);

        $user1 = new User(-1);
        $user1->name = 'user1';
        $user1->accountSum = 100;
        $user1->save();

        $user2 = new User(-2);
        $user2->name = 'user2';
        $user2->accountSum = 200;
        $user2->save();

        $user3 = new User(-3);
        $user3->name = 'user3';
        $user3->accountSum = 300;
        $user3->save();

    }

    protected function tearDown()
    {

        $user1 = new User(-1);
        $user1->delete();
        $user2 = new User(-2);
        $user2->delete();
        $user3 = new User(-3);
        $user3->delete();
        Turn::clear(-11);
        $store = ServiceProvider::getStore();
        $store->setTurn($this->oldTurn);
        $store->delete(Turn::DATATYPE, -11);
        $store->deleteTurnKey(-10);
        $this->fixture = NULL;

    }
    public function testReceiveBets()
    {
        /** @var App $app */
        $app = $this->fixture;
        $store = ServiceProvider::getStore();
        $this->receiveBets();
        $bets = [-1=>12, -2=>6, -3=>3];
        $collect = $app->collect($store->getCurrentTurn());
        $firstBet = current($collect);
        $this->assertTrue(is_object($firstBet));

        foreach ($collect as $key=>$item) {
            $this->assertTrue($item->sum == $bets[$item->userId]);
        }

        Turn::clear(-11);
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
        $turn = new Turn();
        $turn->load(-11);

        foreach ($turn->wins as $win) {
            if ($win->userId == -1) $this->assertEquals(0, $win->sum);
            if ($win->userId == -2) $this->assertEquals(0, $win->sum);
            if ($win->userId == -3) $this->assertEquals(108, $win->sum);
        }

        Turn::clear(-11);
    }

    public function testTurn2()
    {
        /** @var App $app */
        $app = $this->fixture;
        $this->receiveBets();
        $app->nextTurn(10);
        $turn = new Turn();
        $turn->load(-11);

        foreach ($turn->wins as $win) {
            if ($win->userId == -1) $this->assertEquals(36, $win->sum);
            if ($win->userId == -2) $this->assertEquals(0, $win->sum);
            if ($win->userId == -3) $this->assertEquals(0, $win->sum);
        }

        Turn::clear(-11);
    }


}