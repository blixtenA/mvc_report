<?php

namespace App\Proj;

class Room
{
    private $background;
    private $gameObjects;
    private ?array $neighbors;
    private $name;
    private $description; 
    private $roomId;
    private $start;

    public function __construct($roomId, $name, $background, $description = "", $neighbors = null, $start = false)
    {
        $this->roomId = $roomId;
        $this->name = $name;
        $this->background = $background;
        $this->gameObjects = [];
        $this->neighbors = [];
        $this->description = $description;
        $this->start = $start;
    }
    
    public function toJson()
    {
        return json_encode($this);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int 
    {
        return $this->roomId;
    }

    public function addNeighbor(string $direction, Room $room): void
    {
        error_log("adding neighbor to room: ". $this->roomId,0);
        error_log("direction: ". $direction,0);
        error_log("neighbor: ". $room->getId(),0);

        $this->neighbors[$direction] = $room;
    }

    public function getNeighbors(): ?array
    {
        return $this->neighbors;
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

    public function isStart(): bool
    {
        return $this->start ?? false;
    }
}