<?php

namespace App\Proj;

class Player
{
    private $inventory;

    public function __construct()
    {
        $this->inventory = [];
    }

    public function addToInventory(Item $item)
    {
        $this->inventory[] = $item;
    }

    public function removeFromInventory(Item $item)
    {
        $key = array_search($item, $this->inventory);
        if ($key !== false) {
            unset($this->inventory[$key]);
        }
    }

    public function getInventory()
    {
        return $this->inventory;
    }
}