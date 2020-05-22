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

    public function __construct()
    {
        $this->store = ServiceProvider::getStore();
    }

    public function save()
    {
        return $this->store->save(self::DATATYPE, '', $this->id, json_encode($this));
    }

    public function load($id)
    {
        $json = $this->store->load('', $id);
        $this->cast(json_decode($json));
        return $this;
    }

    public static function add($id, $sum)
    {
        $user = new User();
        $user->load($id);
        $user->accountSum += $sum;
        $user->save();
    }

}