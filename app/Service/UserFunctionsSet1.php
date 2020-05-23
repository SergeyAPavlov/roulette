<?php

namespace roulette\Service;


class UserFunctionsSet1 implements UserFunctions
{
    public function beforeTurn()
    {
        echo 'Some text';
    }

    public function afterTurn()
    {

    }

    public function afterReceiveBet()
    {

    }
}