<?php

namespace Tests\App\Proj;

use App\Proj\Game;
use App\Proj\Room;
use App\Proj\Player;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private Game $game;

    public function setUp(): void
    {
        // sample player
        $name = 'John Doe';
        $avatar = 'avatar.jpg';
        $player = new Player($name, $avatar);
        $this->assertInstanceOf(Player::class, $player);

        // sample room
        $sequence = 1;
        $roomId = 1;
        $name = 'Room 1';
        $background = 'room1_background.jpg';
        $description = 'This is Room 1.';
        $neighbors = [];
        $start = true;

        $room = new Room($sequence, $roomId, $name, $background, $description, $neighbors, $start);
        $this->assertInstanceOf(Room::class, $room);

        // Create a game instance
        $this->game = new Game(1);

        // Add the room to the game
        $this->game->addRoom($room);

        // Set the room as the current room
        $this->game->setCurrentRoom($room);

        // Set the player
        $this->game->setPlayer($player);
    }

    public function testGetCurrentRoom(): void
    {
        $currentRoom = $this->game->getCurrentRoom();
        $this->assertInstanceOf(Room::class, $currentRoom);
        $this->assertEquals(1, $currentRoom->getId());
    }

    public function testGetGameState(): void
    {
        $state = "playing";
        $this->game->setGameState($state);
        $this->assertEquals($state, $this->game->getGameState());
    }

    public function testGetGameId(): void
    {
        $gameId = $this->game->getGameId();
        $this->assertEquals(1, $gameId);
    }

    public function testGetRooms(): void
    {
        $rooms = $this->game->getRooms();

        // Assert that the rooms array is not empty
        $this->assertNotEmpty($rooms);

        // Assert that the first room is an instance of Room
        $this->assertInstanceOf(Room::class, $rooms[0]);
    }

    public function testGetPlayer(): void
    {
        $player = $this->game->getPlayer();

        // Assert that the player is an instance of Player
        $this->assertInstanceOf(Player::class, $player);

        // Assert that the player has the correct name
        $this->assertEquals('John Doe', $player->getName());
    }

    public function testMapStartingRoom(): void
    {
        $this->game->mapStartingRoom();

        // Assert that the current room is the starting room
        $currentRoom = $this->game->getCurrentRoom();
        $this->assertInstanceOf(Room::class, $currentRoom);
        $this->assertTrue($currentRoom->isStart());
    }

    
}

