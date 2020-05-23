<?php

namespace roulette\Service;


class Logger
{
    private static $saveFolder = 'log';
    private static $logFileName = 'prod.log';

    /**
     * @param string $logFileName
     */
    public static function setLogFileName(string $logFileName)
    {
        self::$logFileName = $logFileName;
    }


    public static function write($message)
    {
        $savePath = __DIR__ . '/../../' . self::$saveFolder . '/' . self::$logFileName;
        $record = time() . ' : ' . $message . "\n";
        file_put_contents($savePath, $record, FILE_APPEND);
    }

}