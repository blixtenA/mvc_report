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

    /**
     * Constructs a new Player instance.
     *
     * @param string $name The name of the player.
     * @param string|null $avatar The avatar of the player, if available.
     */
    public function __construct(string $name, ?string $avatar = null)
    {
        $this->name = $name;
        $this->avatar = $avatar;
        $this->inventory = [];
    }

    /**
     * Retrieves the avatar of the player.
     *
     * @return string|null The avatar of the player, or null if no avatar is set.
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Retrieves the name of the player.
     *
     * @return string The name of the player.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Adds a GameObject to the inventory.
     *
     * @param GameObject $item The GameObject to add.
     * @return void
     */
    public function addToInventory(GameObject $item): void
    {
        $this->inventory[] = $item;
    }

    /**
     * Removes a GameObject from the inventory.
     *
     * @param GameObject $item The GameObject to remove.
     * @return void
     */
    public function removeFromInventory(GameObject $item): void
    {
        $this->inventory = array_values(array_filter($this->inventory, function ($inventoryItem) use ($item) {
            return $inventoryItem !== $item;
        }));
    }

    /**
     * Retrieves the inventory.
     *
     * @return array The inventory array.
     * @phpstan-ignore-next-line Ignore PHPStan warning for mixed return type.
     */
    public function getInventory(): array
    {
        $inventoryString = '';
        foreach ($this->inventory as $gameObject) {
            $inventoryString .= 'GameObject: ID=' . $gameObject->getObjId() . ', Name=' . $gameObject->getName() . '; ';
//            error_log("options: ". $gameObject->getOptions(),0);
        }
        error_log('Inventory: ' . $inventoryString);

        return $this->inventory;
    }
    

    /**
     * Retrieves an array of inventory item names.
     *
     * @return string[] An array containing the names of the inventory items.
     */
    public function getInventoryNames(): array
    {
        $names = [];
        foreach ($this->inventory as $gameObject) {
            $names[] = $gameObject->getName();
        }
        return $names;
    }

    /**
     * Retrieves a GameObject from the inventory based on its ID.
     *
     * @param int $gameObjectId The ID of the GameObject to retrieve.
     * @return GameObject|null The matching GameObject if found, or null if not found.
     */
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
