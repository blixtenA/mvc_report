<?php

namespace App\Proj;

class Game
{
    private ?Room $currentRoom;
    private string $gameState;
    /**
     * @var array<Room>
     */
    private array $rooms;
    private ?Player $player;

    public function __construct()
    {
        $this->currentRoom = null;
        $this->gameState = 'ongoing';
        $this->rooms = [];
        $this->player = null;
    }

    public function setCurrentRoom(Room $room): void
    {
        $this->currentRoom = $room;
    }

    public function getCurrentRoom(): ?Room
    {
        return $this->currentRoom;
    }

    public function setGameState(string $state): void
    {
        $this->gameState = $state;
    }

    public function getGameState(): string
    {
        return $this->gameState;
    }

    public function addRoom(Room $room): void
    {
        $this->rooms[] = $room;
    }

    /**
 * @return array<Room>
 */
    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }
}
