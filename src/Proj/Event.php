<?php

namespace App\Proj;

class Event
{
    private int $event_id;
    private string $text;
    private string $name;
    /**
     * @var array<int>
     */
    private array $actions;
    private int $location;

    public function __construct(
        int $event_id = 0,
        string $name = '',
        string $text = "something happened",
        int $location = 0,
        array $actions = []
    ) {
        $this->event_id = $event_id;
        $this->text = $text;
        $this->name = $name;
        $this->location = $location;
        $this->actions = $actions;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEventId(): int
    {
        return $this->event_id;
    }

    public function setEventId(int $event_id): void
    {
        $this->event_id = $event_id;
    }

    /**
 * @return array<int>
 */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
 * @param array<int> $actions
 */
    public function setActions(array  $actions): void
    {
        $this->actions = $actions;
    }

    /**
     * Add a single action to the end of the list.
     *
     * @param int $action
     */
    public function addAction(int $action): void
    {
        $this->actions[] = $action;
    }

    public function getLocation(): int
    {
        return $this->location;
    }

    public function setLocation(int $location): void
    {
        $this->location = $location;
    }

    public function initEvent(Game $game, int $gameObjectId, int $eventId, $doctrine): void 
    {
        $entityManager = $doctrine->getManager();
        $roomID = $game->getCurrentRoom()->getId();
            
        /* Retrieve the current game object from the room or player's inventory */
        $gameObject = $game->getCurrentRoom()->getGameObjectById($gameObjectId);
        if (!$gameObject) {
            $gameObject = $game->getPlayer()->getInventoryById($gameObjectId);
        }
    
        if (!$gameObject) {
            error_log("Object not found in controller", 0);
        }
    
        /* Retrieve the event from the database */
        $eventByObjectRepository = $entityManager->getRepository(\App\Entity\EventByObject::class);
        $eventByObject = $eventByObjectRepository->findOneBy([
            'event_id' => $eventId,
            'object_id' => $gameObjectId,
            'location' => $roomID
        ]);
        $eventRepository = $entityManager->getRepository(\App\Entity\Event::class);
        $event = null;

        if ($eventByObject) {
            $location = $eventByObject->getLocation();
            error_log("location ". $location,0);
            error_log("ebo id ". $eventByObject->getId(),0);
            $actions = [
                $eventByObject->getAction1(),
                $eventByObject->getAction2(),
                $eventByObject->getAction3(),
                $eventByObject->getAction4(),
                $eventByObject->getAction5(),
            ];

            /* Find the corresponding event record based on eventId */
            $event = $eventRepository->findOneBy(['id' => $eventByObject->getEventId()]);

            if ($event) {
                $eventId = $event->getId();
                $text = $event->getText();
                $name = $event->getName();

            /* Create a new Event object with the data */
//            $event = new Event($eventId, $name, $text, $location, $actions);

            $this->eventId = $eventId;
            $this->name = $name;
            $this->text = $text;
            $this->location = $location;
            $this->actions = $actions;
        } else {
            error_log("event not found!",0);
        }
    }

    }
}

