<?php

require_once __DIR__ . '/../vendor/autoload.php';


use PHPUnit\Framework\TestCase;
use roulette\Service\RedisStore;

class StoresTest extends TestCase
{

    public function testSaveLoadStores()
    {

        $object = new StdClass();
        $store = new RedisStore();
        $object->id = 34;
        $object->name = 'test';
        $store->save('test', 'test',  34, $object);
        $load = $store->load('test', 34);
        $this->assertTrue($object == $load);
    }

    public function testLoadCollection()
    {

        $object = new StdClass();
        $store = new RedisStore();
        $object->id = 34;
        $object->name = 'test';

        $load = $store->loadCollection('test', 'test');
        $this->assertTrue($object == current($load));
    }

    public function testDeleteStores()
    {
        $store = new RedisStore();

        $store->deleteCollection('test', 'test');
        $load = $store->loadCollection('test',  'test');
        $this->assertTrue( $load == []);
    }

}