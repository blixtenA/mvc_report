<?php

namespace App\Proj;

class Player
{
    private $inventory;
    private $name;
    private $avatar;

    public function __construct($name, $avatar = null)
    {
        $this->name = $name;
        $this->avatar = $avatar;
        $this->inventory = [];
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addToInventory(GameObject $item)
    {
        $this->inventory[] = $item;
    }

    public function removeFromInventory(GameObject $item)
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

    public function getInventoryNames(): array
    {
        $names = [];
        foreach ($this->inventory as $gameObject) {
            $names[] = $gameObject->getName();
        }
        return $names;
    }

    public function getInventoryById($gameObjectId)
    {
        foreach ($this->inventory as $gameObject) {
            if ($gameObject->getObjId() == $gameObjectId) {
                return $gameObject;
            }
        }
        return null;
    }
}