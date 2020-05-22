<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use roulette\Model\User;

class UserTest extends TestCase
{
    public function testSaveLoadUser()
    {

        $user = new User();
        $user->id = 25;
        $user->name = 'Test';
        $user->accountSum = 255;
        $user->save();

        $user2 = new User();
        $user2->load(25);
        $this->assertEquals($user->accountSum, $user2->accountSum);

        $user->delete();
    }
}