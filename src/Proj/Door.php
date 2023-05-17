<?php

namespace App\Proj;

class Door
{
    private $fromRoom;
    private $toRoom;
    private $text;
    private $locked;

    public function __construct($room1, $room2, $text = "", $locked = false)
    {
        $this->fromRoom = $room1;
        $this->toRoom = $room2;
        $this->text = $text;
        $this->locked = $locked;
    }
}