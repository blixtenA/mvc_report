<?php

namespace App\Proj;

class Room
{
    private $background;
    private $gameObjects;
    private $doors;
    private $name;
    private $description; 

    public function __construct($name, $background, $description = "", $doors = null)
    {
        $this->name = $name;
        $this->background = $background;
        $this->gameObjects = [];
        $this->doors = [];
        $this->description = $description;
    }
    
    public function toJson()
    {
        return json_encode($this);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBackground(): string
    {
        return $this->background;
    }

    public function setBackground($background)
    {
        $this->background = $background;
    }

    public function addGameObject(GameObject $gameObject)
    {
        $this->gameObjects[] = $gameObject;
    }

    public function removeGameObject(GameObject $gameObject)
    {
        $index = array_search($gameObject, $this->gameObjects, true);
        if ($index !== false) {
            unset($this->gameObjects[$index]);
            $this->gameObjects = array_values($this->gameObjects);
        }
    }

    public function removeAllGameObjects()
    {
        $this->gameObjects = [];
    }

    public function getGameObjects(): array
    {
        return $this->gameObjects;
    }

    public function getGameObjectNames(): array
    {
        $names = [];
        foreach ($this->gameObjects as $gameObject) {
            $names[] = $gameObject->getName();
        }
        return $names;
    }

    public function getGameObjectById($gameObjectId)
    {
        foreach ($this->gameObjects as $gameObject) {
            if ($gameObject->getObjId() == $gameObjectId) {
                return $gameObject;
            }
        }
        return null;
    }

    public function addDoor(Door $door)
    {
        $this->doors[] = $door;
    }

    public function getDoors()
    {
        return $this->doors;
    }

    public function setDescription(String $text)
    {
        $this->description = $text;
    }

    public function getDescription()
    {
        return $this->description;
    }
}