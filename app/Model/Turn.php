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
        $this->store->save(self::DATATYPE, '', $this->id, json_encode($this));
        return $this;
    }

    public function load($id)
    {
        $json = $this->store->load(self::DATATYPE, $id);
        try {
            $this->cast(json_decode($json));
        } catch (\Exception $e) {
            Throw new \Exception("Incorrect turn id=$id load");
        }
        return $this;
    }

}