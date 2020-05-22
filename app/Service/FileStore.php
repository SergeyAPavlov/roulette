<?php

namespace roulette\Service;



class FileStore implements Stores
{

    private const SAVE_FOLDER = 'files';
    private const CURRENT_TURN_KEY = 'CURRENT_TURN_KEY';
    private $savePath;

    /**
     * FileStore constructor.
     */
    public function __construct()
    {
        $this->savePath = __DIR__ . '/../../' . self::SAVE_FOLDER;
    }


    public function save(string $type, string $collection, string $id,  $object)
    {
            $key = $type.'_'.$collection.'_'.$id;
            $filename = $this->savePath . '/'. $key;
            return file_put_contents($filename, json_encode($object));
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
            $res[] = json_decode(file_get_contents($fileName));
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
        return file_put_contents(self::CURRENT_TURN_KEY, $id);
    }

    public function getCurrentTurn()
    {
        chdir($this->savePath);
        return file_get_contents(self::CURRENT_TURN_KEY);
    }

    public function newTurn()
    {
        chdir($this->savePath);
        $turnId = file_get_contents(self::CURRENT_TURN_KEY);
        return file_put_contents(self::CURRENT_TURN_KEY, $turnId+1);

    }



}