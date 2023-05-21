<?php

namespace Tests\App\Proj;

use App\Proj\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    private Event $event;

    public function setUp(): void
    {
        $eventId = 1;
        $name = 'Event 1';
        $text = 'Something happened';
        $location = 0;
        $actions = [1, 2, 3];

        $this->event = new Event($eventId, $name, $text, $location, $actions);
        $this->assertInstanceOf(Event::class, $this->event);
    }

    public function testGetText(): void
{
    $expectedText = 'Something happened';
    $actualText = $this->event->getText();
    $this->assertSame($expectedText, $actualText);
}

public function testSetText(): void
{
    $newText = 'Updated event text';
    $this->event->setText($newText);
    $updatedText = $this->event->getText();
    $this->assertSame($newText, $updatedText);
}

public function testGetName(): void
{
    $expectedName = 'Event 1';
    $actualName = $this->event->getName();
    $this->assertSame($expectedName, $actualName);
}

public function testSetName(): void
{
    $newName = 'Updated Event Name';
    $this->event->setName($newName);
    $updatedName = $this->event->getName();
    $this->assertSame($newName, $updatedName);
}

public function testGetEventId(): void
{
    $expectedEventId = 1;
    $actualEventId = $this->event->getEventId();
    $this->assertSame($expectedEventId, $actualEventId);
}

public function testSetEventId(): void
{
    $newEventId = 2;
    $this->event->setEventId($newEventId);
    $updatedEventId = $this->event->getEventId();
    $this->assertSame($newEventId, $updatedEventId);
}

public function testGetActions(): void
{
    $expectedActions = [1, 2, 3];
    $actualActions = $this->event->getActions();
    $this->assertSame($expectedActions, $actualActions);
}

public function testSetActions(): void
{
    $newActions = [4, 5, 6];
    $this->event->setActions($newActions);
    $updatedActions = $this->event->getActions();
    $this->assertSame($newActions, $updatedActions);
}

public function testAddAction(): void
{
    $newAction = 4;
    $this->event->addAction($newAction);
    $updatedActions = $this->event->getActions();
    $expectedActions = [1, 2, 3, 4];
    $this->assertSame($expectedActions, $updatedActions);
}

public function testGetLocation(): void
{
    $expectedLocation = 0;
    $actualLocation = $this->event->getLocation();
    $this->assertSame($expectedLocation, $actualLocation);
}

public function testSetLocation(): void
{
    $newLocation = 1;
    $this->event->setLocation($newLocation);
    $updatedLocation = $this->event->getLocation();
    $this->assertSame($newLocation, $updatedLocation);
}


}