<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use roulette\App;
use roulette\Service\ServiceProvider;
use roulette\Model\User;
use roulette\Model\Turn;
use roulette\Model\Bet;

class AppTest extends TestCase
{
    protected $fixture;
    protected $store;

    protected function setUp()
    {


        ServiceProvider::setDevStores();
        ServiceProvider::setCurrentStore('RedisStore');
        $this->store = ServiceProvider::getStore();

        $this->fixture = new App();
        $this->store->setTurn(11);
        Turn::clear(11);

        $user1 = new User(1);
        $user1->name = 'user1';
        $user1->accountSum = 100;
        $user1->save();

        $user2 = new User(2);
        $user2->name = 'user2';
        $user2->accountSum = 200;
        $user2->save();

        $user3 = new User(3);
        $user3->name = 'user3';
        $user3->accountSum = 300;
        $user3->save();

        $user4 = new User(4);
        $user4->name = 'user4';
        $user4->accountSum = 400;
        $user4->save();

    }

    protected function tearDown()
    {

        $user1 = new User(1);
        $user1->delete();
        $user2 = new User(2);
        $user2->delete();
        $user3 = new User(3);
        $user3->delete();
        $user4 = new User(4);
        $user4->delete();
        Turn::clear(11);
        /** @var \roulette\Service\Stores $store */
        $store = $this->store;
        $store->delete(Turn::DATATYPE, 11);
        $store->deleteTurnKey();
        $this->fixture = null;

    }

    // проверка правильности сохранения ставок
    public function testReceiveBets()
    {
        /** @var App $app */
        $app = $this->fixture;
        /** @var \roulette\Service\Stores $store */
        $store = $this->store;
        $this->receiveBets();
        $bets = [1 => 12, 2 => 6, 3 => 3];
        $collect = $app->collect($store->getCurrentTurn());
        $firstBet = current($collect);
        $this->assertTrue(is_object($firstBet));

        foreach ($collect as $key => $item) {
            $this->assertTrue($item->sum == $bets[$item->userId]);
        }

        Turn::clear(11);
    }

    public function receiveBets()
    {
        /** @var App $app */
        $app = $this->fixture;
        $app->receiveBet(1, 12, 'twelve', 5);
        $app->receiveBet(2, 6, 'black');
        $app->receiveBet(3, 3, 'one', 4);
    }

    // проверка расчета результатов
    public function testCountTurn()
    {
        $bets = Bet::createBets(
            [
                [1, 12, 'twelve', 15],
                [2, 6, 'black', 0],
                [3, 3, 'one', 4]
            ]
        );
        $winField = 4;
        /** @var App $app */
        $app = $this->fixture;
        $wins = $app->countTurn($bets, $winField);

        foreach ($wins as $win) {
            if ($win->userId == 1) $this->assertEquals(36, $win->sum);
            if ($win->userId == 2) $this->assertEquals(0, $win->sum);
            if ($win->userId == 3) $this->assertEquals(108, $win->sum);
        }

    }

    // проверка ожидаемых результатов игры по массиву wins
    public function testTurn()
    {
        /** @var App $app */
        $app = $this->fixture;
        $this->receiveBets();
        $app->nextTurn(4);
        $turn = new Turn();
        $turn->load(11);

        foreach ($turn->wins as $win) {
            if ($win->userId == 1) $this->assertEquals(0, $win->sum);
            if ($win->userId == 2) $this->assertEquals(0, $win->sum);
            if ($win->userId == 3) $this->assertEquals(108, $win->sum);
        }

        Turn::clear(11);
    }


    public function testTurn2()
    {
        /** @var App $app */
        $app = $this->fixture;
        $this->receiveBets();
        $app->nextTurn(10);
        $turn = new Turn();
        $turn->load(11);

        foreach ($turn->wins as $win) {
            if ($win->userId == 1) $this->assertEquals(36, $win->sum);
            if ($win->userId == 2) $this->assertEquals(0, $win->sum);
            if ($win->userId == 3) $this->assertEquals(0, $win->sum);
        }

        Turn::clear(11);
    }

    // проверка ожидаемых результатов игры по счетам игроков
    public function testTurnResults()
    {
        /** @var App $app */
        $app = $this->fixture;

        $user1 = new User(1);
        $user1->name = 'user1';
        $user1->accountSum = 100;
        $user1->save();

        $user2 = new User(2);
        $user2->name = 'user2';
        $user2->accountSum = 200;
        $user2->save();

        $user3 = new User(3);
        $user3->name = 'user3';
        $user3->accountSum = 300;
        $user3->save();

        $user4 = new User(4);
        $user4->name = 'user4';
        $user4->accountSum = 400;
        $user4->save();

        $app->receiveBet(1, 12, 'three', 9);
        $app->receiveBet(2, 6, 'black');
        $app->receiveBet(3, 3, 'one', 11);

        $app->nextTurn(11);
        $turn = new Turn();
        $turn->load(11);

        $users = User::loadUsers();
        $this->assertEquals(232, $users[1]->accountSum);
        $this->assertEquals(206, $users[2]->accountSum);
        $this->assertEquals(405, $users[3]->accountSum);

        Turn::clear(11);
    }

    // проверка расчета с несколькими ставками от одного юзера
    public function testMultiBets()
    {
        /** @var App $app */
        $app = $this->fixture;

        $user1 = new User(1);
        $user1->name = 'user1';
        $user1->accountSum = 100;
        $user1->save();

        $user2 = new User(2);
        $user2->name = 'user2';
        $user2->accountSum = 100;
        $user2->save();

        $app->receiveBet(1, 12, 'three', 9);
        $app->receiveBet(2, 10, 'black');
        $app->receiveBet(2, 20, 'twelve', 11);

        $app->nextTurn(11);
        $turn = new Turn();
        $turn->load(11);

        $users = User::loadUsers();
        $this->assertEquals(232, $users[1]->accountSum);
        $this->assertEquals(150, $users[2]->accountSum);


        Turn::clear(11);
    }

}