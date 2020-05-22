<?php

namespace roulette\Model;
use roulette\Helpers\Castable;
use roulette\Service\ServiceProvider;
use roulette\Service\Stores;


class Bet
{
    use Castable;
    const DATATYPE = 'bet';

    /** @var  string */
    public $id;
    /** @var  integer */
    public $timestamp;
    /** @var string */
    public $type;
    /** @var  integer */
    public $userId;
    /** @var  integer */
    public $choose;
    /** @var  integer */
    public $sum;
    /** @var  integer */
    public $turnId;
    /** @var  Stores */
    private $store;

    /**
     * Bet constructor.
     */
    public function __construct()
    {
        $this->store = ServiceProvider::getStore();
    }

    public function create($object)
    {
        $this->cast($object);
        if (empty($this->timestamp)) $this->timestamp = time();
        if (empty($this->id)) $this->id = uniqid($this->timestamp);
        return $this;
    }


    public function save()
    {
        return $this->store->save(self::DATATYPE, $this->turnId, $this->id, json_encode($this));
    }

    public function load($id)
    {
        $json = $this->store->load(self::DATATYPE, $id);
        try {
            $this->cast(json_decode($json));
        } catch (\Exception $e) {
            Throw new \Exception("Incorrect bet id=$id load");
        }
        return $this;
    }


}