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

    public function getGameId(): int
    {
        return $this->gameId;
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

    public function initGame($doctrine): void 
    {
        $entityManager = $doctrine->getManager();

        $gameRepository = $entityManager->getRepository(\App\Entity\Game::class);
        $map = $gameRepository->findBy(['game_id' => $this->gameId]); 

        /* Extract room IDs and positions from the retrieved game entity */
        $roomIDs = [];
        $roomPositions = [];
        foreach ($map as $gameEntity) {
            $roomIDs[] = $gameEntity->getRoomId();
            $roomPositions[$gameEntity->getRoomId()] = [
                'pos_x' => $gameEntity->getPosX(),
                'pos_y' => $gameEntity->getPosY(),
            ];
        }

        /* Fetch the rooms from the Room entity using the extracted room IDs */
        $roomRepository = $entityManager->getRepository(\App\Entity\Room::class);
        $rooms = $roomRepository->findBy(['id' => $roomIDs]);

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

            $room = new Room(
                $roomEntity->getId(),
                $roomEntity->getName(),
                $roomEntity->getBackground(),
                $roomEntity->getDescription(),
                null,
                $start
            );
            $room->loadObjects(1, $doctrine);

            $roomObjects[$roomEntity->getId()] = $room;      
        }
        
        /* Find neighboring rooms based on coordinates */
        foreach ($rooms as $roomEntity) {
        
            $roomPositionX = $roomPositions[$roomEntity->getId()]['pos_x'];
            $roomPositionY = $roomPositions[$roomEntity->getId()]['pos_y'];

            foreach ($rooms as $neighborEntity) {
                $neighborPositionX = $roomPositions[$neighborEntity->getId()]['pos_x'];
                $neighborPositionY = $roomPositions[$neighborEntity->getId()]['pos_y'];

                if ($neighborPositionX === $roomPositionX && $neighborPositionY === $roomPositionY - 1) {
                    $roomObjects[$roomEntity->getId()]->addNeighbor('South', $roomObjects[$neighborEntity->getId()]);
                } elseif ($neighborPositionX === $roomPositionX && $neighborPositionY === $roomPositionY + 1) {
                    $roomObjects[$roomEntity->getId()]->addNeighbor('North', $roomObjects[$neighborEntity->getId()]);
                } elseif ($neighborPositionX === $roomPositionX - 1 && $neighborPositionY === $roomPositionY) {
                    $roomObjects[$roomEntity->getId()]->addNeighbor('West', $roomObjects[$neighborEntity->getId()]);
                } elseif ($neighborPositionX === $roomPositionX + 1 && $neighborPositionY === $roomPositionY) {
                    $roomObjects[$roomEntity->getId()]->addNeighbor('East', $roomObjects[$neighborEntity->getId()]);
                }
            }

            $this->addRoom($roomObjects[$roomEntity->getId()]);
        }

        $this->mapStartingRoom();

    }

}
