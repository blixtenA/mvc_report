<?php

namespace Tests\App\Proj;

use App\Proj\Room;
use PHPUnit\Framework\TestCase;
use App\Proj\GameObject;

class RoomTest extends TestCase
{
    private Room $room;
    private Room $neighborRoom;

    protected function setUp(): void
    {
        /* Set up a sample room */
        $sequence = 1;
        $roomId = 1;
        $name = 'Room 1';
        $background = 'room1_background.jpg';
        $description = 'This is Room 1.';
        $neighbors = [];
        $start = null;

        $this->room = new Room($sequence, $roomId, $name, $background, $description, $neighbors, $start);
        $this->assertInstanceOf(Room::class, $this->room);

        /* Create a dummy neighbor room (Room 2) */
        $neighborRoomId = 2;
        $neighborName = 'Room 2';
        $neighborBackground = 'room2_background.jpg';
        $neighborDescription = 'This is Room 2.';
        $neighborNeighbors = [];
        $neighborStart = null;

        $this->neighborRoom = new Room($sequence, $neighborRoomId, $neighborName, $neighborBackground, $neighborDescription, $neighborNeighbors, $neighborStart);
        
    }

    private function createDummyGameObject(): GameObject
    {
        $objId = 1;
        $image = 'image.jpg';
        $name = 'Example Object';
        $positionX = 10;
        $positionY = 20;
        $positionZ = 0;
        $options = ['option1', 'option2'];
        $effect = 'example effect';
        $width = 100;
        $height = 200;
        $image2 = 'image2.jpg';

        return new GameObject(
            $objId,
            $image,
            $name,
            $positionX,
            $positionY,
            $positionZ,
            $options,
            $effect,
            $width,
            $height,
            $image2
        );
    }

    public function testGetName(): void
    {
        $expectedName = 'Room 1';
        $actualName = $this->room->getName();

        $this->assertSame($expectedName, $actualName);
    }

    public function testGetSequence(): void
    {
        $expectedSequence = 1;
        $actualSequence = $this->room->getSequence();

        $this->assertSame($expectedSequence, $actualSequence);
    }

    public function testGetId(): void
    {
        $expectedId = 1;
        $actualId = $this->room->getId();

        $this->assertSame($expectedId, $actualId);
    }

    public function testAddNeighbor(): void
    {
        $direction = 'North';
        $this->room->addNeighbor($direction, $this->neighborRoom);
        
        $neighbors = $this->room->getNeighbors();
        $this->assertArrayHasKey($direction, $neighbors);
        $this->assertSame($this->neighborRoom, $neighbors[$direction]);
    }

    public function testGetNeighbors(): void
    {
        $direction = 'North';
        $this->room->addNeighbor($direction, $this->neighborRoom);
        
        $neighbors = $this->room->getNeighbors();
        $this->assertIsArray($neighbors);
        $this->assertArrayHasKey($direction, $neighbors);
        $this->assertSame($this->neighborRoom, $neighbors[$direction]);
    }

    public function testGetBackground(): void
    {
        $expectedBackground = 'room1_background.jpg';
        $actualBackground = $this->room->getBackground();

        $this->assertSame($expectedBackground, $actualBackground);
    }

    public function testAddGameObject(): void
    {
        $gameObject = $this->createDummyGameObject();
        $this->room->addGameObject($gameObject);
        
        $gameObjects = $this->room->getGameObjects();
        $this->assertCount(1, $gameObjects);
        $this->assertSame($gameObject, $gameObjects[0]);
    }

    public function testRemoveGameObject(): void
    {
        $gameObject1 = $this->createDummyGameObject();
        $gameObject2 = $this->createDummyGameObject();
        $gameObject3 = $this->createDummyGameObject();
        $this->room->addGameObject($gameObject1);
        $this->room->addGameObject($gameObject2);
        $this->room->addGameObject($gameObject3);
        
        $this->room->removeGameObject($gameObject2);
        $gameObjects = $this->room->getGameObjects();

        $this->assertCount(2, $gameObjects);
        $this->assertSame($gameObject1, $gameObjects[0]);
        $this->assertSame($gameObject3, $gameObjects[1]);
    }

    public function testSetBackground(): void
    {
        $background = 'new_background.jpg';
        $this->room->setBackground($background);
        
        $actualBackground = $this->room->getBackground();
        $this->assertSame($background, $actualBackground);
    }

    public function testRemoveAllGameObjects(): void
    {
        $gameObject1 = $this->createDummyGameObject();
        $gameObject2 = $this->createDummyGameObject();
        $gameObject3 = $this->createDummyGameObject();
        $this->room->addGameObject($gameObject1);
        $this->room->addGameObject($gameObject2);
        $this->room->addGameObject($gameObject3);
        
        $this->room->removeAllGameObjects();
        $gameObjects = $this->room->getGameObjects();

        $this->assertCount(0, $gameObjects);
    }

    public function testGetGameObjects(): void
    {
        $gameObject1 = $this->createDummyGameObject();
        $gameObject2 = $this->createDummyGameObject();
        $this->room->addGameObject($gameObject1);
        $this->room->addGameObject($gameObject2);
        
        $gameObjects = $this->room->getGameObjects();

        $this->assertCount(2, $gameObjects);
        $this->assertSame($gameObject1, $gameObjects[0]);
        $this->assertSame($gameObject2, $gameObjects[1]);
    }

    public function testGetGameObjectNames(): void
    {
        $gameObject1 = $this->createDummyGameObject();
        $gameObject2 = $this->createDummyGameObject();
        $this->room->addGameObject($gameObject1);
        $this->room->addGameObject($gameObject2);
        
        $names = $this->room->getGameObjectNames();

        $this->assertCount(2, $names);
        $this->assertSame($gameObject1->getName(), $names[0]);
        $this->assertSame($gameObject2->getName(), $names[1]);
    }

    public function testGetGameObjectById(): void
    {
        $gameObject1 = new GameObject(1, 'image1.jpg', 'Object 1');
        $gameObject2 = new GameObject(2, 'image2.jpg', 'Object 2');
        $gameObject3 = new GameObject(3, 'image3.jpg', 'Object 3');
        $this->room->addGameObject($gameObject1);
        $this->room->addGameObject($gameObject2);
        $this->room->addGameObject($gameObject3);
    
        $foundGameObject = $this->room->getGameObjectById(2);
    
        $this->assertSame($gameObject2, $foundGameObject);
    }
    

    public function testSetDescription(): void
    {
        $description = 'This is an updated room description.';
        $this->room->setDescription($description);

        $actualDescription = $this->room->getDescription();
        $this->assertSame($description, $actualDescription);
    }

    public function testGetDescription(): void
    {
        $description = 'This is a room description.';
        $this->room->setDescription($description);

        $actualDescription = $this->room->getDescription();
        $this->assertSame($description, $actualDescription);
    }

    public function testIsStartWhenStartIsNull(): void
    {
        $isStart = $this->room->isStart();

        $this->assertFalse($isStart);
    }

    public function testIsStartWhenStartIsTrue(): void
    {
        $start = true;
        $room = new Room(1, 1, 'Room 1', 'room1_background.jpg', 'This is Room 1.', [], $start);

        $isStart = $room->isStart();

        $this->assertTrue($isStart);
    }

    public function testIsStartWhenStartIsFalse(): void
    {
        $start = false;
        $room = new Room(1, 1, 'Room 1', 'room1_background.jpg', 'This is Room 1.', [], $start);

        $isStart = $room->isStart();

        $this->assertFalse($isStart);
    }
}