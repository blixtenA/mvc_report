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
    private int $gameId;

    public function __construct(int $gameId)
    {
        $this->gameId = $gameId;
        $this->currentRoom = null;
        $this->gameState = 'ongoing';
        $this->rooms = [];
        $this->player = null;
    }

    /**
     * Set the current room in the game.
     *
     * @param Room $room The room object.
     * @return void
     */
    public function setCurrentRoom(Room $room): void
    {
        $this->currentRoom = $room;
    }

    /**
     * Get the current room in the game.
     *
     * @return Room|null The current room object, or null if not set.
     */
    public function getCurrentRoom(): ?Room
    {
        return $this->currentRoom;
    }

    /**
     * Set the game state.
     *
     * @param string $state The game state.
     * @return void
     */
    public function setGameState(string $state): void
    {
        $this->gameState = $state;
    }

    /**
     * Get the game state.
     *
     * @return string The game state.
     */
    public function getGameState(): string
    {
        return $this->gameState;
    }

    /**
     * Get the game ID.
     *
     * @return int The game ID.
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * Add a room to the game.
     *
     * @param Room $room The room object to add.
     * @return void
     */
    public function addRoom(Room $room): void
    {
        $this->rooms[] = $room;
    }


    /**
     * Get the rooms in the game.
     *
     * @return array<Room> The array of rooms.
     */
    public function getRooms(): array
    {
        return $this->rooms;
    }

    /**
     * Set the player in the game.
     *
     * @param Player $player The player object.
     * @return void
     */
    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    /**
     * Get the player in the game.
     *
     * @return Player|null The player object, or null if not set.
     */
    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    /**
     * Map the starting room as the current room.
     *
     * @return void
     */
    public function mapStartingRoom(): void
    {
        $startingRoom = null;
        foreach ($this->getRooms() as $room) {
            if ($room->isStart()) {
                $startingRoom = $room;
                break;
            }
        }
        $this->setCurrentRoom($startingRoom);
    }

/**
 * Get the room data for positioning etc
 *
 * @param array $map
 * @return array
 * */
/* @phpstan-ignore-next-line warning-code */
    private function extractRoomData(array $map): array
    {
        $roomIDs = [];
        $roomPositions = [];
        
        foreach ($map as $gameEntity) {
            $roomID = $gameEntity->getRoomId();
            $roomIDs[] = $roomID;
            $roomPositions[$roomID] = [
                'pos_x' => $gameEntity->getPosX(),
                'pos_y' => $gameEntity->getPosY(),
            ];
        }

        return [$roomIDs, $roomPositions];
    }

    /**
     * Create a Room object based on the given Room entity and start flag.
     *
     * @param \App\Entity\Room $roomEntity The Room entity.
     * @param bool|null $start The start flag indicating if the room is a starting room.
     * @return \App\Proj\Room The created Room object.
     */
    private function createRoom(\App\Entity\Room $roomEntity, ?bool $start): \App\Proj\Room
    {
        $room = new Room(
            1, /* initial sequence */
            $roomEntity->getId(),
            $roomEntity->getName(),
            $roomEntity->getBackground(),
            $roomEntity->getDescription(),
            null,
            $start
        );

        return $room;
    }
    
    /**
     * Fetch the game map from the database using the EntityManager.
     *
     * @param mixed $entityManager The EntityManager object.
     * @return array<\App\Entity\Game> The fetched game map.
     */
    private function fetchGameMap($entityManager): array
    {
        $gameRepository = $entityManager->getRepository(\App\Entity\Game::class);
        return $gameRepository->findBy(['game_id' => $this->gameId]);
    }

    /**
     * Fetch the rooms from the database based on the given room IDs.
     *
     * @param mixed $entityManager The EntityManager object.
     * @param array<int> $roomIDs The array of room IDs to fetch.
     * @return array<\App\Entity\Room> The fetched rooms.
     */
    private function fetchRooms($entityManager, array $roomIDs): array
    {
        $roomRepository = $entityManager->getRepository(\App\Entity\Room::class);
        return $roomRepository->findBy(['id' => $roomIDs]);
    }

    /**
     * Game init
     *
     * @param mixed $doctrine The doctrine instance
     * @return void
     */
    public function initGame(mixed $doctrine): void 
    {
        $entityManager = $doctrine->getManager();
        $map = $this->fetchGameMap($entityManager);
        [$roomIDs, $roomPositions] = $this->extractRoomData($map);
        $rooms = $this->fetchRooms($entityManager, $roomIDs);

        /* Prepare Rooms and load them into the game */
        $roomObjects = [];
        foreach ($rooms as $roomEntity) {
            $roomID = $roomEntity->getId();
            $start = null;
        
            foreach ($map as $gameEntity) {
                if ($gameEntity->getRoomId() === $roomID) {
                    $start = $gameEntity->isStart();
                    break;
                }
            }
            $room = $this->createRoom($roomEntity, $start);
            $room->loadObjects(1, $doctrine);
            $roomObjects[$roomEntity->getId()] = $room;   
        }
        
        /* Specialized functions for populating and placing the rooms */
        $this->findAndSetNeighbors($roomObjects, $roomPositions);
        $this->addRoomsToGame($roomObjects);
        $this->mapStartingRoom();
    }

    /**
     * Find and set the neighbors of the rooms
     *
     * @param array<Room> $roomObjects An array of room objects
     * @param array<int,array<string,int>> $roomPositions An array of room positions
     * @return void
     */
    private function findAndSetNeighbors(array &$roomObjects, array $roomPositions): void
    {
        foreach ($roomObjects as $roomEntity) {
            $roomPositionX = $roomPositions[$roomEntity->getId()]['pos_x'];
            $roomPositionY = $roomPositions[$roomEntity->getId()]['pos_y'];

            $this->findAndAddNeighbors($roomEntity, $roomObjects, $roomPositions, $roomPositionX, $roomPositionY);
        }
    }

    /**
     * Find and add neighbors to a room based on coordinates
     *
     * @param Room $roomEntity The room entity to find neighbors for
     * @param array<Room> $roomObjects An array of room objects
     * @param array<int,array<string,int>> $roomPositions An array of room positions
     * @param int $roomPositionX The X coordinate of the room
     * @param int $roomPositionY The Y coordinate of the room
     * @return void
     */
    private function findAndAddNeighbors(Room $roomEntity, array &$roomObjects, array $roomPositions, int $roomPositionX, int $roomPositionY): void
    {
        $neighborPositions = $this->getNeighborPositions($roomPositionX, $roomPositionY);

        foreach ($roomObjects as $neighborEntity) {
            $neighborPositionX = $roomPositions[$neighborEntity->getId()]['pos_x'];
            $neighborPositionY = $roomPositions[$neighborEntity->getId()]['pos_y'];

            if (in_array([$neighborPositionX, $neighborPositionY], $neighborPositions)) {
                $direction = $this->getNeighborDirection($roomPositionX, $roomPositionY, $neighborPositionX, $neighborPositionY);
                $roomEntity->addNeighbor($direction, $roomObjects[$neighborEntity->getId()]);
            }
        }
    }

    /**
     * Get neighbor positions based on room coordinates
     *
     * @param int $roomPositionX The X coordinate of the room
     * @param int $roomPositionY The Y coordinate of the room
    * @return array<int,array<int,int>> An array of neighbor positions [[$x1, $y1], [$x2, $y2], ...]
    */
    private function getNeighborPositions(int $roomPositionX, int $roomPositionY): array
    {
        $neighborPositions = [
            [$roomPositionX, $roomPositionY - 1], // South
            [$roomPositionX, $roomPositionY + 1], // North
            [$roomPositionX - 1, $roomPositionY], // West
            [$roomPositionX + 1, $roomPositionY], // East
        ];

        return $neighborPositions;
    }

    /**
     * Get neighbor direction based on room and neighbor coordinates
     *
     * @param int $roomPositionX The X coordinate of the room
     * @param int $roomPositionY The Y coordinate of the room
     * @param int $neighborPositionX The X coordinate of the neighbor
     * @param int $neighborPositionY The Y coordinate of the neighbor
     * @return string The direction of the neighbor ('South', 'North', 'West', 'East'), or an empty string if no match
     */
    private function getNeighborDirection(int $roomPositionX, int $roomPositionY, int $neighborPositionX, int $neighborPositionY): string
    {
        if ($neighborPositionX === $roomPositionX && $neighborPositionY === $roomPositionY - 1) {
            return 'South';
        } elseif ($neighborPositionX === $roomPositionX && $neighborPositionY === $roomPositionY + 1) {
            return 'North';
        } elseif ($neighborPositionX === $roomPositionX - 1 && $neighborPositionY === $roomPositionY) {
            return 'West';
        } elseif ($neighborPositionX === $roomPositionX + 1 && $neighborPositionY === $roomPositionY) {
            return 'East';
        }

        return '';
    }

    /**
     * Add rooms to the game
     *
     * @param Room[] $roomObjects Array of Room objects
     */
    private function addRoomsToGame(array $roomObjects): void
    {
        foreach ($roomObjects as $roomEntity) {
            $this->addRoom($roomEntity);
        }
    }

}
