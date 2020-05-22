<?php

namespace roulette;

use roulette\Model\Bet;
use roulette\Model\Rules;
use roulette\Model\Turn;
use roulette\Model\User;
use roulette\Service\ServiceProvider;
use roulette\Service\Stores;

class App
{
    /** @var  Stores */
    private $store;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->store = ServiceProvider::getStore();
    }

    public function receiveBet($userId, $sum, $type, $choose = 0)
    {
        $bet = new Bet();
        $bet->set();
        $bet->userId = $userId;
        $bet->sum = $sum;
        $bet->type = $type;
        $bet->choose = $choose;
        $bet->turnId = $this->store->getCurrentTurn();
        return $bet->save();
    }

    public function countTurn($bets, $winField)
    {
        $wins = [];
        foreach ($bets as $bet) {
            $win = new \stdClass();
            $win->betId = $bet->id;
            $win->userId = $bet->userId;
            $win->sum = Rules::checkField($bet->type, $bet->choose, $winField, $bet->sum);
            $wins[] = $win;
        }
        return $wins;
    }

    public function collect($turnId)
    {
        return $this->store->loadCollection(Bet::DATATYPE, $turnId);
    }

    public function payOff($bets, $wins)
    {
        foreach ($bets as $bet) {
            User::add($bet->userId, -$bet->sum);
        }
        foreach ($wins as $win) {
            User::add($win->userId, $win->sum);
        }
    }

    public function nextTurn()
    {
        $current = $this->store->getCurrentTurn();
        $this->store->newTurn();
        $turn = New Turn();
        $turn->id = $current;
        $turn->bets = $this->collect($current);
        $turn->winField = mt_rand(0, 36);
        $turn->wins = $this->countTurn($turn->bets, $turn->winField);
        $this->payOff($turn->bets, $turn->wins);
        $turn->save();
    }
}