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
     * @param null|string $id
     */
    public function __construct($id = null)
    {
        $this->store = ServiceProvider::getStore();
        if (!is_null($id)) $this->id = $id;
    }

    public function create($object)
    {
        $this->cast($object);
        $this->set();
        return $this;
    }

    public function set()
    {
        if (empty($this->timestamp)) $this->timestamp = time();
        if (empty($this->id)) $this->id = uniqid($this->timestamp);
        return $this;
    }

    public function save()
    {
        return $this->store->save(self::DATATYPE, $this->turnId, $this->id, $this);
    }

    public function load($id)
    {
        $object = $this->store->load(self::DATATYPE, $id);
        if (!is_object($object)) Throw new \Exception("Incorrect bet id=$id load");
        $this->cast($object);
        return $this;
    }

    public function delete()
    {
        $this->store->delete(self::DATATYPE, $this->id);
    }

}