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

    // @phpstan-ignore-next-line
    public function __construct(
        int $roomId,
        string $name,
        string $background,
        string $description = "",
        ?array $neighbors = null,
        ?bool $start = null
    ) {
        $this->roomId = $roomId;
        $this->name = $name;
        $this->background = $background;
        $this->gameObjects = [];
        $this->neighbors = [];
        $this->description = $description;
        $this->start = $start;
    }
    
/*    public function toJson(): string 
    {
        return json_encode($this);
    } */

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

    /**
     * @return ?array<Room>
     */
    public function getNeighbors(): ?array
    {
        return $this->neighbors;
    }    

    public function getBackground(): string
    {
        return $this->background;
    }

    public function setBackground(string $background): void
    {
        $this->background = $background;
    }

    public function addGameObject(GameObject $gameObject): void
    {
        $this->gameObjects[] = $gameObject;
    }

    public function removeGameObject(GameObject $gameObject): void
    {
        $index = array_search($gameObject, $this->gameObjects, true);
        if ($index !== false) {
            unset($this->gameObjects[$index]);
            $this->gameObjects = array_values($this->gameObjects);
        }
    }

    public function removeAllGameObjects(): void
    {
        $this->gameObjects = [];
    }

    /**
     * @return array<GameObject>
     */
    public function getGameObjects(): array
    {
        return $this->gameObjects;
    }

    /**
     * @return array<string>
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
     * @param mixed $gameObjectId
     * @return ?GameObject
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

    public function setDescription(String $text): void
    {
        $this->description = $text;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isStart(): bool
    {
        return $this->start ?? false;
    }
}