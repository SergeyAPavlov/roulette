<?php

namespace roulette\Service;



class FileStore implements Stores
{

    private static $saveFolder = 'files';
    private static $currentTurnKey = 'CURRENT_TURN_KEY';
    private $savePath;

    /**
     * FileStore constructor.
     */
    public function __construct()
    {
        $this->savePath = __DIR__ . '/../../' . self::$saveFolder;
        if (!file_exists($this->savePath . '/'.self::$currentTurnKey)) {
            file_put_contents($this->savePath . '/'.self::$currentTurnKey, '');
        }
    }

    /**
     * @param string $saveFolder
     */
    public static function setSaveFolder(string $saveFolder)
    {
        self::$saveFolder = $saveFolder;
    }

    /**
     * @param string $currentTurnKey
     */
    public static function setCurrentTurnKey(string $currentTurnKey)
    {
        self::$currentTurnKey = $currentTurnKey;
    }


    public function save(string $type, string $collection, string $id,  $object)
    {
            $key = $type.'_'.$collection.'_'.$id;
            $filename = $this->savePath . '/'. $key;
            return file_put_contents($filename, json_encode($object), LOCK_EX);
    }

    public function load(string $type, string $id)
    {
        $pattern = $type.'_*_'.$id;
        chdir($this->savePath);
        $fileName = current(glob($pattern));
        return json_decode(file_get_contents($fileName));
    }

    public function delete(string $type, string $id)
    {
        $pattern = $type.'_*_'.$id;
        chdir($this->savePath);
        $fileName = current(glob($pattern));
        if (file_exists($fileName)) return unlink($fileName);
        else return null;
    }

    public function loadCollection(string $type, string $collection)
    {

        $pattern = $type.'_'.$collection.'_*';
        chdir($this->savePath);
        $fileNames = glob($pattern);
        $res = [];
        foreach ($fileNames as $fileName) {
            $value = json_decode(file_get_contents($fileName));
            $res[$value->id] = $value;
        }
        return $res;
    }

    public function deleteCollection(string $type, string $collection)
    {
        $pattern = $type.'_'.$collection.'_*';
        chdir($this->savePath);
        $fileNames = glob($pattern);
        foreach ($fileNames as $fileName) {
            unlink($fileName);
        }
    }

    public function setTurn($id)
    {
        chdir($this->savePath);
        return file_put_contents(self::$currentTurnKey, $id,LOCK_EX);
    }

    public function getCurrentTurn()
    {
        chdir($this->savePath);
        return file_get_contents(self::$currentTurnKey);
    }

    public function newTurn()
    {
        chdir($this->savePath);
        $turnId = file_get_contents(self::$currentTurnKey);
        return file_put_contents(self::$currentTurnKey, $turnId+1, LOCK_EX);

    }

    public function deleteTurnKey()
    {
        chdir($this->savePath);
        return unlink(self::$currentTurnKey);
    }

}