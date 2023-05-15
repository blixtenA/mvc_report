<?php

namespace App\Proj;

class Event
{
    private $eventImages;
    private $positionX;
    private $positionY;
    private $animationDelay;

    public function __construct(array $eventImages, $positionX, $positionY, $animationDelay = 100)
    {
        $this->eventImages = $eventImages;
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->animationDelay = $animationDelay;
    }

    public function getEventImages()
    {
        return $this->eventImages;
    }

    public function getPositionX()
    {
        return $this->positionX;
    }

    public function getPositionY()
    {
        return $this->positionY;
    }

    public function getAnimationDelay()
    {
        return $this->animationDelay;
    }

    public function playAnimation()
    {
        foreach ($this->eventImages as $image) {
            // Display the image at the specified position on screen
            // Delay the animation by $this->animationDelay milliseconds
            // You can use JavaScript or any other method based on your game implementation
        }
    }

    public function triggerEvent()
    {
        // Perform actions related to the event
        // For example, killing the player or triggering other game events
        // You can implement your event logic here
    }
}
