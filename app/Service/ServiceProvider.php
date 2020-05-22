<?php

namespace roulette\Service;


class ServiceProvider
{
    const CURRENT_STORE = 'RedisStore';
    const CURRENT_FUNCTIONS_SET = 'Set1';

    public static function getStore()
    {
        if (self::CURRENT_STORE == 'RedisStore') {
            return new RedisStore();
        } else {
            Throw new \Exception('Unknown store service');
        }
    }

    public static function getUserFunctions()
    {
        if (self::CURRENT_FUNCTIONS_SET == 'Set1') {
            return new UserFunctionsSet1();
        } else {
            Throw new \Exception('Unknown functions set');
        }
    }
}