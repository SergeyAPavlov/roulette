<?php

namespace roulette\Service;

interface Stores
{

    public  function save (string $type, string $collection, string $id, $object);
    public  function load (string $type, string $id);
    public function delete(string $type, string $id);
    public  function loadCollection (string $type, string $collection);
    public  function deleteCollection (string $type, string $collection);

    public  function getCurrentTurn(); // текущий раунд рулетки

    public  function newTurn();
    // используется только для отладки
    public  function setTurn($id);
    public  function deleteTurnKey();

}