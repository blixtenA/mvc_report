<?php

namespace App\Proj;

class Room
{
    private string $background;
    /**
     * @var array|GameObject[]
     */
    private array $gameObjects = [];
    /**
     * @var array<Room>|null
     */
    private ?array $neighbors = null;
    private string $name;
    private string $description;
    private int $roomId;
    private ?bool $start;
    private int $sequence;

    /**
     * Room constructor.
     *
     * @param int $roomId The ID of the room.
     * @param string $name The name of the room.
     * @param string $background The background of the room.
     * @param string $description The description of the room.
     * @param array|null $neighbors An array of neighboring rooms.
     * @param bool|null $start Indicates if the room is a starting room.
     */
    // @phpstan-ignore-next-line
    public function __construct(
        int $sequence,
        int $roomId,
        string $name,
        string $background,
        string $description = "",
        ?array $neighbors = null,
        ?bool $start = null
    ) {
        $this->sequence = $sequence;
        $this->roomId = $roomId;
        $this->name = $name;
        $this->background = $background;
        $this->gameObjects = [];
        $this->neighbors = [];
        $this->description = $description;
        $this->start = $start;
    }

    /**
     * Retrieves the name of the room.
     *
     * @return string The name of the room.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retrieves the sequence of the room.
     *
     * @return int The sequence of the room.
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }
    
    /**
     * Retrieves the ID of the room.
     *
     * @return int The ID of the room.
     */
    public function getId(): int 
    {
        return $this->roomId;
    }

    /**
     * Adds a neighboring room in the specified direction.
     *
     * @param string $direction The direction of the neighboring room.
     * @param Room $room The neighboring room object.
     * @return void
     */
    public function addNeighbor(string $direction, Room $room): void
    {
        $this->neighbors[$direction] = $room;
    }

    /**
     * Retrieves the neighboring rooms.
     *
     * @return Room[]|null An array of neighboring rooms, or null if there are no neighbors.
     */
    public function getNeighbors(): ?array
    {
        return $this->neighbors;
    }    

    /**
     * Retrieves the background of the room.
     *
     * @return string The background of the room.
     */
    public function getBackground(): string
    {
        return $this->background;
    }

    /**
     * Sets the background of the room.
     *
     * @param string $background The background to set for the room.
     * @return void
     */
    public function setBackground(string $background): void
    {
        $this->background = $background;
    }

    /**
     * Adds a game object to the collection.
     *
     * @param GameObject $gameObject The game object to add.
     * @return void
     */
    public function addGameObject(GameObject $gameObject): void
    {
        $this->gameObjects[] = $gameObject;
    }

    /**
     * Removes a game object from the collection.
     *
     * @param GameObject $gameObject The game object to remove.
     * @return void
     */
    public function removeGameObject(GameObject $gameObject): void
    {
        $index = array_search($gameObject, $this->gameObjects, true);
        if ($index !== false) {
            unset($this->gameObjects[$index]);
            $this->gameObjects = array_values($this->gameObjects);
        }
    }

    /**
     * Removes all game objects from the collection.
     *
     * @return void
     */
    public function removeAllGameObjects(): void
    {
        $this->gameObjects = [];
    }

    /**
     * Retrieves an array of game objects.
     *
     * @return GameObject[] An array containing the game objects.
     */
    public function getGameObjects(): array
    {
        return $this->gameObjects;
    }

    /**
     * Retrieves an array of game object names.
     *
     * @return string[] An array containing the names of the game objects.
     */
    public function getGameObjectNames(): array
    {
        $names = [];
        foreach ($this->gameObjects as $gameObject) {
            $names[] = $gameObject->getName();
        }
        return $names;
    }

    /**
     * Retrieves a GameObject from the gameObjects array based on its ID.
     *
     * @param mixed $gameObjectId The ID of the GameObject to retrieve.
     * @return GameObject|null The matching GameObject if found, or null if not found.
     */
    public function getGameObjectById($gameObjectId): ?GameObject
    {
        foreach ($this->gameObjects as $gameObject) {
            if ($gameObject->getObjId() == $gameObjectId) {
                return $gameObject;
            }
        }
        return null;
    }

    /**
     * Sets the description of the room.
     *
     * @param string $text The description text.
     * @return void
     */
    public function setDescription(String $text): void
    {
        $this->description = $text;
    }

    /**
     * Retrieves the description of the room.
     *
     * @return string The description of the room.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Checks if the room is a starting room.
     *
     * @return bool True if the room is a starting room, false otherwise.
     */
    public function isStart(): bool
    {
        return $this->start ?? false;
    }

    /**
     * Load objects into the room.
     */
    public function loadObjects(int $sequence, mixed $doctrine): void
    {
        $entityManager = $doctrine->getManager();
        $roomID = $this->getId();

        /* Load Objects into each Room */
        $objectByRoomRepository = $entityManager->getRepository(\App\Entity\ObjectByRoom::class);
        $gameObjectsRepository = $entityManager->getRepository(\App\Entity\GameObject::class);
        
        $objectByRooms = $objectByRoomRepository->findBy(['room_id' => $roomID]);
        $objectIDs = [];

        foreach ($objectByRooms as $objectByRoom) {
            if ($objectByRoom->getSequence() === $sequence) {
                $objectIDs[] = $objectByRoom->getObjectId();
            }
        }

        $gameObjects = $gameObjectsRepository->findBy(['id' => $objectIDs]);

        foreach ($gameObjects as $gameObject) {
            /* Find the object_by_room entry for the current GameObject */
            $objectByRoom = $objectByRoomRepository->findOneBy([
                'room_id' => $roomID,
                'object_id' => $gameObject->getId(),
                'sequence' => $sequence,
            ]);

            /* Retrieve the position values from object_by_room */
            $newGameObject = new GameObject();
            $newGameObject->initFromRoom($objectByRoom, $gameObject, $doctrine, $roomID);
            $this->addGameObject($newGameObject);
        }
    }
}