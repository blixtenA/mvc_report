<?php

namespace App\Proj;

class Player
{
    /**
     * @var array|GameObject[]
     */
    private array $inventory;
    private string $name;
    private ?string $avatar;

    public function __construct(string $name, ?string $avatar = null)
    {
        $this->name = $name;
        $this->avatar = $avatar;
        $this->inventory = [];
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addToInventory(GameObject $item): void
    {
        $this->inventory[] = $item;
    }

    public function removeFromInventory(GameObject $item): void
    {
        $key = array_search($item, $this->inventory);
        if ($key !== false) {
            unset($this->inventory[$key]);
        }
    }

    /**
 * @return array
 * @phpstan-ignore-next-line
 */
    public function getInventory(): array
    {
        return $this->inventory;
    }

    /**
 * @return string[]
 */
    public function getInventoryNames(): array
    {
        $names = [];
        foreach ($this->inventory as $gameObject) {
            $names[] = $gameObject->getName();
        }
        return $names;
    }

    public function getInventoryById(int $gameObjectId): ?GameObject
    {
        foreach ($this->inventory as $gameObject) {
            if ($gameObject->getObjId() == $gameObjectId) {
                return $gameObject;
            }
        }
        return null;
    }
}
