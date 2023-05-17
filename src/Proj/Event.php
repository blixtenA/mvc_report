<?php

namespace App\Proj;

class Event
{
    private $eventImages;
    private $positionX;
    private $positionY;
    private $animationDelay;
    private $route;
    private $text;
    private $actions;
    private $eventId;
    private $name;

    public function __construct(
        $eventId,
        $name,
        $route = null,
        array $eventImages = null,
        $positionX = 250,
        $positionY = 250,
        $animationDelay = 100,
        $text = "something happened",
        $actions = []
    ) {
        $this->eventImages = $eventImages ?? [];
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->animationDelay = $animationDelay;
        $this->route = $route;
        $this->text = $text;
        $this->actions = $actions;
        $this->eventId = $eventId;
        $this->name =$name;
    }

    public function getEventImages(): array
    {
        return $this->eventImages;
    }

    public function getPositionX(): int
    {
        return $this->positionX;
    }

    public function getPositionY(): int
    {
        return $this->positionY;
    }

    public function getAnimationDelay(): int
    {
        return $this->animationDelay;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function setActions(array $actions): void
    {
        $this->actions = $actions;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): void
    {
        $this->eventId = $eventId;
    }

    public function playAnimation()
    {
        foreach ($this->eventImages as $image) {
            // Display the image at the specified position on screen
            // Delay the animation by $this->animationDelay milliseconds
            // You can use JavaScript or any other method based on your game implementation
        }
    }

    public function actions()
    {
        // Perform actions related to the event
        // For example, killing the player or triggering other game events
        // You can implement your event logic here
    }
}
