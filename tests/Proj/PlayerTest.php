<?php

namespace Tests\App\Proj;

use App\Proj\Player;
use App\Proj\GameObject;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    private Player $player;

    protected function setUp(): void
    {
        parent::setUp();

        $name = 'John Doe';
        $avatar = 'avatar.jpg';

        $this->player = new Player($name, $avatar);
    }

    public function testGetAvatar(): void
    {
        $avatar = 'avatar.jpg';
        $this->assertSame($avatar, $this->player->getAvatar());
    }

    public function testGetName(): void
    {
        $name = 'John Doe';
        $this->assertSame($name, $this->player->getName());
    }

    public function testAddToInventory(): void
    {
        $item = new GameObject();
        $this->player->addToInventory($item);

        $inventory = $this->player->getInventory();
        $this->assertCount(1, $inventory);
        $this->assertSame($item, $inventory[0]);
    }

    public function testRemoveFromInventory(): void
    {
        $item1 = new GameObject();
        $item2 = new GameObject();
        $item3 = new GameObject();
    
        $this->player->addToInventory($item1);
        $this->player->addToInventory($item2);
        $this->player->addToInventory($item3);
    
        $this->player->removeFromInventory($item2);
    
        $inventory = $this->player->getInventory();
        $this->assertCount(2, $inventory);
        $this->assertNotContains($item2, $inventory);
    }
    
    

    public function testGetInventory(): void
    {
        $item1 = new GameObject();
        $item2 = new GameObject();
        $item3 = new GameObject();

        $this->player->addToInventory($item1);
        $this->player->addToInventory($item2);
        $this->player->addToInventory($item3);

        $inventory = $this->player->getInventory();

        $this->assertCount(3, $inventory);
        $this->assertSame([$item1, $item2, $item3], $inventory);
    }

    public function testGetInventoryNames(): void
    {
        $item1 = new GameObject();
        $item1->setName('Item 1');
        $item2 = new GameObject();
        $item2->setName('Item 2');
        $item3 = new GameObject();
        $item3->setName('Item 3');

        $this->player->addToInventory($item1);
        $this->player->addToInventory($item2);
        $this->player->addToInventory($item3);

        $names = $this->player->getInventoryNames();

        $this->assertCount(3, $names);
        $this->assertSame(['Item 1', 'Item 2', 'Item 3'], $names);
    }

    public function testGetInventoryById(): void
    {
        $item1 = new GameObject();
        $item1->setObjId(1);
        $item2 = new GameObject();
        $item2->setObjId(2);
        $item3 = new GameObject();
        $item3->setObjId(3);

        $this->player->addToInventory($item1);
        $this->player->addToInventory($item2);
        $this->player->addToInventory($item3);

        $foundItem = $this->player->getInventoryById(2);

        $this->assertSame($item2, $foundItem);
    }

    public function testGetInventoryByIdNotFound(): void
{
    $item1 = new GameObject(1);
    $item2 = new GameObject(2);
    $item3 = new GameObject(3);

    $this->player->addToInventory($item1);
    $this->player->addToInventory($item2);
    $this->player->addToInventory($item3);

    $nonExistentId = 4;
    $result = $this->player->getInventoryById($nonExistentId);

    $this->assertNull($result);
}

}