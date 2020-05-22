<?php

namespace roulette\Model;
use roulette\Helpers\Castable;
use roulette\Service\Stores;
use roulette\Service\ServiceProvider;

class User
{
    use Castable;
    const DATATYPE = 'user';

    /** @var  int */
    public $id;
    /** @var  string */
    public $name;
    /** @var  int */
    public $accountSum;

    /** @var  Stores */
    private $store;


    /**
     * User constructor.
     * @param integer $id
     */
    public function __construct($id = null)
    {
        $this->store = ServiceProvider::getStore();
        if (!is_null($id)) $this->id = $id;
    }


    /**
     * сохранить в хранилище
     * @return bool
     */
    public function save()
    {
        return $this->store->save(self::DATATYPE, '', $this->id, $this);
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
        if (!is_object($object)) Throw new \Exception("Incorrect user id=$id load");
        $this->cast($object);
        return $this;
    }


    /**
     * удалить из хранилища
     */
    public function delete()
    {
        $this->store->delete(self::DATATYPE, $this->id);
    }


    /**
     * увеличить (уменьшить) размер счета игрока
     * @param integer $id
     * @param integer $sum
     */
    public static function add($id, $sum)
    {
        $user = new User();
        $user->load($id);
        $user->accountSum += $sum;
        $user->save();
    }

}