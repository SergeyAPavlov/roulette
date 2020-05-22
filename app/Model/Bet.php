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
    /** @var string - тип ставки */
    public $type;
    /** @var  integer */
    public $userId;
    /** @var  integer - поле на которое игрок поставил фишку */
    public $choose;
    /** @var  integer - размер ставки */
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


    /**
     * действия при первом создании ставки
     * @return $this
     */
    public function set()
    {
        if (empty($this->timestamp)) $this->timestamp = time();
        if (empty($this->id)) $this->id = uniqid($this->timestamp);
        return $this;
    }


    /**
     * Сохранить ставку в хранилище
     * @return bool
     */
    public function save()
    {
        return $this->store->save(self::DATATYPE, $this->turnId, $this->id, $this);
    }

    /**
     * Загрузить ставку из хранилища
     * @param int $id
     * @return $this
     * @throws \Exception
     */
    public function load($id)
    {
        $object = $this->store->load(self::DATATYPE, $id);
        if (!is_object($object)) Throw new \Exception("Incorrect bet id=$id load");
        return $this->cast($object);
    }


    /**
     * удалить ставку из хранилища
     */
    public function delete()
    {
        return $this->store->delete(self::DATATYPE, $this->id);
    }

}