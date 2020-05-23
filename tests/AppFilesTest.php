<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../tests/AppTest.php';

use roulette\App;
use roulette\Service\ServiceProvider;
use roulette\Model\User;
use roulette\Model\Turn;


class AppFilesTest extends AppTest
{
    protected $store;
    protected $fixture;

    protected function setUp()
    {


        ServiceProvider::setDevStores();
        ServiceProvider::setCurrentStore('FileStore');
        $this->store = ServiceProvider::getStore();

        $this->fixture = new App();
        Turn::clear(11);
        $this->store->setTurn(11);


        $user1 = new User(1);
        $user1->name = 'user1';
        $user1->accountSum = 100;
        $user1->save();

        $user2 = new User(2);
        $user2->name = 'user2';
        $user2->accountSum = 200;
        $user2->save();

        $user3 = new User(3);
        $user3->name = 'user3';
        $user3->accountSum = 300;
        $user3->save();

    }

    protected function tearDown()
    {

        $user1 = new User(1);
        $user1->delete();
        $user2 = new User(2);
        $user2->delete();
        $user3 = new User(3);
        $user3->delete();
        Turn::clear(11);
        /** @var \roulette\Service\Stores $store */
        $store = $this->store;
        $store->delete(Turn::DATATYPE, 11);
        $store->deleteTurnKey();
        $this->fixture = NULL;

    }


}