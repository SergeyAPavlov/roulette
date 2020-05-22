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

    public function __construct($id = null)
    {
        $this->store = ServiceProvider::getStore();
        if (!is_null($id)) $this->id = $id;
    }

    public function save()
    {
        return $this->store->save(self::DATATYPE, '', $this->id, $this);
    }

    public function load($id)
    {
        $json = $this->store->load(self::DATATYPE, $id);
        if (is_null($json)) return null;
        try {
            $this->cast($json);
        } catch (\Exception $e) {
            Throw new \Exception("Incorrect user id=$id load");
        }
        return $this;
    }

    public function delete()
    {
        $this->store->delete(self::DATATYPE, $this->id);
    }

    public static function add($id, $sum)
    {
        $user = new User();
        $user->load($id);
        $user->accountSum += $sum;
        $user->save();
    }

}