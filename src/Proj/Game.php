<?php

namespace App\Proj;

class Game
{
    private $currentRoom;
    private $gameState;
    private $rooms;
    private $player;

    public function __construct()
    {
        $this->currentRoom = null;
        $this->gameState = 'ongoing';
        $this->rooms = [];
        $this->player = null;
    }

    public function setCurrentRoom(Room $room)
    {
        $this->currentRoom = $room;
    }

    public function getCurrentRoom()
    {
        return $this->currentRoom;
    }

    public function setGameState($state)
    {
        $this->gameState = $state;
    }

    public function getGameState()
    {
        return $this->gameState;
    }

    public function addRoom(Room $room)
    {
        $this->rooms[] = $room;
    }

    public function getRooms()
    {
        return $this->rooms;
    }

    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    public function getPlayer()
    {
        return $this->player;
    }
}
