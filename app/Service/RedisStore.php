<?php

namespace roulette\Service;

use Rediska;
use Rediska_Key;

class RedisStore implements Stores
{
    private $redis;
    private const CURRENT_TURN_KEY = 'CURRENT_TURN_KEY';
    private static $prefix = 'pre:';

    /**
     * RedisStore constructor.
     */
    public function __construct()
    {
        $this->redis = new Rediska();
    }

    /**
     * @param string $prefix
     */
    public static function setPrefix(string $prefix)
    {
        self::$prefix = $prefix;
    }



    public function save(string $type, string $collection, string $id,  $object)
    {
            $key = new Rediska_Key(self::$prefix."$type:$collection:$id");
            return $key->setValue($object);
    }

    public function load(string $type, string $id)
    {
        $keys = $this->redis->getKeysByPattern(self::$prefix."$type:*:$id");
        $key = new Rediska_Key(current($keys));
        return $key->getValue();
    }

    public function delete(string $type, string $id)
    {
        $keys = $this->redis->getKeysByPattern(self::$prefix."$type:*:$id");
        $key = new Rediska_Key(current($keys));
        return $key->delete();
    }

    public function loadCollection(string $type, string $collection)
    {

        $keys = $this->redis->getKeysByPattern(self::$prefix."$type:$collection:*");
        $res = [];
        foreach ($keys as $k) {
            $key = new Rediska_Key($k);
            $value = $key->getValue();
            $res[$value->id] = $value;
        }
        return $res;
    }

    public function deleteCollection(string $type, string $collection)
    {

        $keys = $this->redis->getKeysByPattern(self::$prefix."$type:$collection:*");
        $res = [];
        foreach ($keys as $k) {
            $key = new Rediska_Key($k);
            $res[] = $key->delete();
        }
        return $res;
    }

    public function getCurrentTurn()
    {
        $key = new Rediska_Key(self::$prefix.self::CURRENT_TURN_KEY);
        $id = $key->getValue();
        if (empty($id)) return 1;
        else return $id;
    }

    public function newTurn()
    {
        $key = new Rediska_Key(self::$prefix.self::CURRENT_TURN_KEY);
        $new = $this->getCurrentTurn() + 1;
        return $key->setValue($new);
    }

    public function setTurn($id)
    {
        $key = new Rediska_Key(self::$prefix.self::CURRENT_TURN_KEY);
        return $key->setValue($id);
    }

    public function deleteTurnKey()
    {
        $key = new Rediska_Key(self::$prefix.self::CURRENT_TURN_KEY);
        return $key->delete();
    }

}