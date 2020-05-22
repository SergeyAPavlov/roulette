<?php

namespace roulette\Service;


interface  UserFunctions
{
    public  function beforeTurn();
    public  function afterTurn();
    public  function afterReceiveBet();

}