<?php

namespace roulette;

use roulette\Model\Bet;
use roulette\Model\Rules;
use roulette\Model\Turn;
use roulette\Model\User;
use roulette\Service\ServiceProvider;
use roulette\Service\Stores;
use roulette\Service\Logger;

class App
{
    /** @var  Stores */
    private $store;
    private $functionsSet;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->store = ServiceProvider::getStore();
        $this->functionsSet = ServiceProvider::getUserFunctions();
    }


    /**
     * принять ставку и сохранить в хранилище
     * @param int $userId
     * @param int $sum
     * @param string $type
     * @param int $choose
     * @return bool
     */
    public function receiveBet($userId, $sum, $type, $choose = 0)
    {
        $bet = new Bet();
        $bet->set();
        $bet->userId = $userId;
        $bet->sum = $sum;
        $bet->type = $type;
        $bet->choose = $choose;
        $bet->turnId = $this->store->getCurrentTurn();
        $functionsSet = ServiceProvider::getUserFunctions();
        $functionsSet->afterReceiveBet();
        return $bet->save();
    }


    /**
     * рассчитать выигрыши по ставкам
     * @param Bet[] $bets
     * @param int $winField
     * @return array
     */
    public function countTurn($bets, $winField)
    {
        $wins = [];
        foreach ($bets as $bet) {
            try {
                $win = new \stdClass();
                $win->betId = $bet->id;
                $win->userId = $bet->userId;
                $win->sum = Rules::checkField($bet->type, $bet->choose, $winField, $bet->sum);
                $wins[$win->betId] = $win;
            } catch (\Throwable $t) {
                Logger::write($t->getMessage());
            }
        }
        return $wins;
    }


    /**
     * ставки указанного раунда из хранилища
     * @param int $turnId
     * @return mixed
     */
    public function collect($turnId)
    {
        return $this->store->loadCollection(Bet::DATATYPE, $turnId);
    }


    /**
     * снять ставки со счетов игроков
     * и расплатиться по рассчитанным выигрышам
     * @param Bet[] $bets
     * @param array $wins
     */
    public function payOff($bets, $wins)
    {
        // todo: этот расчет бы надо сделать неделимой трансакцией - восстанавливать исходное состояние при исключении
        foreach ($bets as $bet) {
            User::add($bet->userId, -$bet->sum);
        }
        foreach ($wins as $win) {
            User::add($win->userId, $win->sum);
        }
    }


    /**
     * подведение итогов раунда и переход к следующему раунду
     * @param integer $winField - возможность задать выигрывшее поле (для тестирования)
     */
    public function nextTurn($winField = null)
    {
        $this->functionsSet->beforeTurn();
        $current = $this->store->getCurrentTurn();
        $this->store->newTurn();
        $turn = New Turn();
        $turn->id = $current;
        $turn->bets = $this->collect($current);

        if (is_null($winField)) $turn->winField = mt_rand(0, 36);
        else $turn->winField = $winField;

        $turn->wins = $this->countTurn($turn->bets, $turn->winField);
        $this->payOff($turn->bets, $turn->wins);
        $turn->save();
        $this->functionsSet->afterTurn();
    }
}