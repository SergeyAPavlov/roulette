<?php

namespace roulette\Service;


class ServiceProvider
{
    const CURRENT_STORE = 'RedisStore';

    public static function getStore()
    {
        if (self::CURRENT_STORE == 'RedisStore') {
            return new RedisStore();
        } else {
            Throw new \Exception('Unknown store service');
        }
    }
}