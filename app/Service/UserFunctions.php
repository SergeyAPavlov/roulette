<?php

namespace roulette\Service;


/**
 * Interface UserFunctions
 * @package roulette\Service
 * реализация пункта ТЗ "Добавьте возможность вызова пользовательского кода определённые моменты игры"
 * что это такое, я не очень понял - предположил, что нужно дать возможность легко выбирать набор функций,
 * которые запускаются в определенных точках приложения
 */
interface  UserFunctions
{
    public function beforeTurn();

    public function afterTurn();

    public function afterReceiveBet();

}