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

    /**
     * @phpstan-ignore-next-line
     */
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

    /**
     * Get the text of the event.
     *
     * @return string The event text.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the text of the event.
     *
     * @param string $text The event text.
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Get the name of the event.
     *
     * @return string The event name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the event.
     *
     * @param string $name The event name.
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the ID of the event.
     *
     * @return int The event ID.
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * Set the ID of the event.
     *
     * @param int $event_id The event ID.
     * @return void
     */
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

    /**
     * Get the location of the event.
     *
     * @return int The event location.
     */
    public function getLocation(): int
    {
        return $this->location;
    }

    /**
     * Set the location of the event.
     *
     * @param int $location The event location.
     * @return void
     */
    public function setLocation(int $location): void
    {
        $this->location = $location;
    }

    /**
     * Initialize the event.
     *
     * @param Game              $game          The game instance.
     * @param GameObject        $gameObject    The game object.
     * @param int               $eventId       The ID of the event.
     * @param mixed             $doctrine      The Doctrine entity manager.
     * @param int               $location      The room id (or inventory = 0)
     * @return void
     */
    public function initEvent(Game $game, GameObject $gameObject, int $eventId, int $location, $doctrine): void 
    {
        $entityManager = $doctrine->getManager();
        $gameObjectId = $gameObject->getObjId();
                
        /* Retrieve the event from the database */
        $eventByObjectRepository = $entityManager->getRepository(\App\Entity\EventByObject::class);
        $eventByObject = $eventByObjectRepository->findOneBy([
            'event_id' => $eventId,
            'object_id' => $gameObjectId,
            'location' => $location
        ]);
        $eventRepository = $entityManager->getRepository(\App\Entity\Event::class);
        $event = null;

        if ($eventByObject) {
            $location = $eventByObject->getLocation();
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
            // @phpstan-ignore-next-line
            $this->eventId = $eventId;
            $this->name = $name;
            $this->text = $text;
            $this->location = $location;
            $this->actions = $actions;
        }
    }

    }
}

