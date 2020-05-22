<?php

require_once __DIR__ . '/../vendor/autoload.php';


use PHPUnit\Framework\TestCase;
use roulette\Service\RedisStore;
use roulette\Service\FileStore;

class StoresTest extends TestCase
{

    public function testSaveLoadRedis()
    {

        $object = new StdClass();
        $store = new RedisStore();
        $object->id = 34;
        $object->name = 'test';
        $store->save('test', 'test',  34, $object);
        $load = $store->load('test', 34);
        $this->assertTrue($object == $load);
    }

    public function testLoadCollectionRedis()
    {

        $object = new StdClass();
        $store = new RedisStore();
        $object->id = 34;
        $object->name = 'test';

        $load = $store->loadCollection('test', 'test');
        $this->assertTrue($object == current($load));
    }

    public function testDeleteRedis()
    {
        $store = new RedisStore();

        $store->deleteCollection('test', 'test');
        $load = $store->loadCollection('test',  'test');
        $this->assertTrue( $load == []);
    }

    public function testSaveLoadFiles()
    {

        $object = new StdClass();
        $store = new FileStore();
        $object->id = 34;
        $object->name = 'test';
        $store->save('test', 'test',  34, $object);
        $load = $store->load('test', 34);
        $this->assertTrue($object == $load);
    }

    public function testLoadCollectionFiles()
    {

        $object = new StdClass();
        $store = new FileStore();
        $object->id = 34;
        $object->name = 'test';

        $load = $store->loadCollection('test', 'test');
        $this->assertTrue($object == current($load));
    }

    public function testDeleteCollectionFiles()
    {

        $store = new FileStore();
        $store->deleteCollection('test', 'test');
        $load = $store->loadCollection('test',  'test');
        $this->assertTrue( $load == []);

    }
}