<?php

namespace roulette\Model;
use roulette\Helpers\Castable;
use roulette\Service\Stores;
use roulette\Service\ServiceProvider;

/**
 * объект раунда рулетки
 * Class Turn
 * @package roulette\Model
 */
class Turn
{
    use Castable;
    const DATATYPE = 'turn';

    public $id;
    public $startTimestamp;
    public $endTimestamp;

    public $bets = [];
    public $winField;
    public $wins = [];

    /** @var  Stores */
    private $store;


    public function __construct()
    {
        $this->store = ServiceProvider::getStore();
    }

    /**
     * @param array|object $object
     * @return $this
     */
    public function create($object)
    {
        $this->cast($object);
        $this->startTimestamp = time();
        return $this;
    }

    /**
     * сохранить в хранилище
     * @return $this
     */
    public function save()
    {
        $this->store->save(self::DATATYPE, '', $this->id, $this);
        return $this;
    }

    /**
     * загрузить из хранилища
     * @param integer $id
     * @return $this
     * @throws \Exception
     */
    public function load($id)
    {
        $object = $this->store->load(self::DATATYPE, $id);
        if (!is_object($object)) Throw new \Exception("Incorrect turn id=$id load");
        $this->cast($object);
        return $this;
    }

    /**
     * удалить из хранилища
     * @return $this
     */
    public function delete()
    {
        $this->store->delete(self::DATATYPE, $this->id);
        return $this;
    }

    /**
     * удалить из хранилища все ставки указанного раунда
     * @param integer $id
     */
    public static function clear($id)
    {
        $store = ServiceProvider::getStore();
        $betsObjects = $store->loadCollection(Bet::DATATYPE, $id);
        foreach ($betsObjects as $betObject) {
            $bat = new Bet($betObject->id);
            $bat->delete();
        }
    }

}