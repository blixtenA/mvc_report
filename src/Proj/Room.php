<?php

namespace App\Proj;

class Room
{
    private $background;
    private $gameObjects;
    private $events;
    private $exits;

    public function __construct($background)
    {
        $this->background = $background;
        $this->gameObjects = [];
        $this->events = [];
        $this->doors = [];
        $this->description = "";
    }
    
    public function toJson()
    {
        return json_encode($this);
    }

    public function getBackground(): string
    {
        return $this->background;
    }

    public function addGameObject(GameObject $gameObject)
    {
        $this->gameObjects[] = $gameObject;
    }

    public function getGameObjects(): array
    {
        return $this->gameObjects;
    }

    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    public function getEvents()
    {
        return $this->events;
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