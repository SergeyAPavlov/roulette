<?php

namespace roulette\Service;


class ServiceProvider
{
    private static $currentStore = 'RedisStore';
    private static $currentFunctionsSet = 'Set1';

    /**
     * @param string $currentStore
     */
    public static function setCurrentStore(string $currentStore)
    {
        self::$currentStore = $currentStore;
    }

    /**
     * @param string $currentFunctionsSet
     */
    public static function setCurrentFunctionsSet(string $currentFunctionsSet)
    {
        self::$currentFunctionsSet = $currentFunctionsSet;
    }


    /**
     * создать сервис хранилища
     * @return Stores
     * @throws \Exception
     */
    public static function getStore()
    {
        if (self::$currentStore == 'RedisStore') {
            return new RedisStore();
        } elseif (self::$currentStore == 'FileStore') {
            return new FileStore();
        } else {
            Throw new \Exception('Unknown store service');
        }
    }

    /**
     *
     * @return UserFunctions
     * @throws \Exception
     */
    public static function getUserFunctions()
    {
        if (self::$currentFunctionsSet == 'Set1') {
            return new UserFunctionsSet1();
        } else {
            Throw new \Exception('Unknown functions set');
        }
    }

    public static function setDevStores()
    {
        RedisStore::setPrefix('devtest:');
        FileStore::setSaveFolder('testfiles');
        Logger::setLogFileName('tests.log');
    }
}