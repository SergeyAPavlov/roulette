<?php

namespace roulette\Model;
use roulette\Helpers\Castable;
use roulette\Service\Stores;
use roulette\Service\ServiceProvider;

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

    public function create($object)
    {
        $this->cast($object);
        $this->startTimestamp = time();
        return $this;
    }

    public function save()
    {
        $this->store->save(self::DATATYPE, '', $this->id, $this);
        return $this;
    }

    public function load($id)
    {
        $object = $this->store->load(self::DATATYPE, $id);
        if (!is_object($object)) Throw new \Exception("Incorrect turn id=$id load");
        $this->cast($object);
        return $this;
    }

    public function delete()
    {
        $this->store->delete(self::DATATYPE, $this->id);
        return $this;
    }

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