<?php

namespace App\Proj;

use Doctrine\Persistence\ManagerRegistry;
use App\Proj\Game;
use App\Proj\Room;
use App\Proj\Player;
use App\Entity\Event;
use App\Proj\Action;
use App\Entity\ObjectByRoom;
use App\Entity\EventByObject;
use App\Entity\GameObject as AppGameObject;

class GameObject
{
    private string $image;
    private int $positionX;
    private int $positionY;
    // @phpstan-ignore-next-line
    private ?int $positionZ = 0;
    private bool $clickable;
    private string $name;
    /**
     * @var array<string, mixed>
     */
    private ?array $options = null;
    private int $objId;
    private ?string $effect;
    private ?int $width = null;
    private ?int $height = null;
    private ?ManagerRegistry $doctrine;
    

    // @phpstan-ignore-next-line
    public function __construct(
        int $objId = 0,
        string $image = '',
        string $name = '',
        int $positionX = 0,
        int $positionY = 0,
        int $positionZ = 0,
        bool $clickable = false,
        ?array $options = null,
        ?string $effect = null,
        ?int $width = null,
        ?int $height = null,
        ?ManagerRegistry $doctrine = null
    ) {
        $this->objId = $objId;
        $this->image = $image;
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->positionZ = $positionZ;
        $this->clickable = $clickable;
        $this->name = $name;
        $this->options = $options;
        $this->effect = $effect;
        $this->width = $width;
        $this->height = $height;
        $this->doctrine = $doctrine;
    }

    /**
     * Retrieves the object id.
     *
     * @return int The object id.
     */
    public function getObjId(): int 
    {
        return $this->objId;
    }

    public function setObjId(int $objId): void 
    {
        $this->objId = $objId;
    }

    /**
     * Retrieves the effect.
     *
     * @return string The effect as string.
     */
    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function setEffect($effect): void
    {
        $this->effect = $effect;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getPositionX(): int
    {
        return $this->positionX;
    }

    public function setPositionX(int $position): void
    {
        $this->positionX = $position;
    }

    public function getPositionY(): int
    {
        return $this->positionY;
    }

    public function setPositionY(int $position): void
    {
        $this->positionY = $position;
    }

    public function getPositionZ(): int
    {
        return $this->positionY;
    }

    public function setPositionZ(int $position): void
    {
        $this->positionZ = $position;
    }


    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $value): void
    {
        $this->height = $value;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $value): void
    {
        $this->width = $value;
    }

    public function isClickable(): bool
    {
        return $this->clickable;
    }

    public function setClickable(bool $clickable): void
    {
        $this->clickable = $clickable;
    }

        /**
     * Check if there are any click options available.
     *
     * @return bool
     */
    public function hasClickOptions(): bool
    {
        return !empty($this->options);
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function onClick(): array
    {
        $options = [];
        foreach ($this->options as $eventId => $eventName) {
            $options[] = [
                'eventId' => $eventId,
                'eventName' => $eventName,
            ];
        }
        return $options;
    }
    
    /**
     * @return array<mixed, mixed>
     */
    public function getOptions(): array
    {
        $options = [];
        foreach ($this->options as $optionKey => $eventId) {
            $options[$optionKey] = $eventId;
        }
        return $options;
    }
    
    public function getEventByOption(mixed $option): mixed
    {
        return isset($this->options[$option]) ? $this->options[$option] : null;
    }
    
    public function addOption(int|string $key, mixed $value): void
    {
        /** @phpstan-ignore-next-line */
        $this->options[$key] = $value;
        error_log("Added option: key = $key, value = $value", 0);
    }
    
    /**
     * Initialize and populate the GameObject from a room.
     *
     */
    public function initFromRoom($objectByRoom, $gameObject, $doctrine, $roomID)
    {
        $entityManager = $doctrine->getManager();

        $positionX = $objectByRoom->getPositionX();
        $positionY = $objectByRoom->getPositionY();
        $positionZ = $objectByRoom->getPositionZ();
        $height = $objectByRoom->getHeight();
        $width = $objectByRoom->getWidth();

        $this->objId = $gameObject->getId();
        $this->image = $gameObject->getImage();
        $this->name = $gameObject->getName();
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->positionZ = $positionZ;
        $this->clickable = $gameObject->isClickable();
        $this->options = null;
        $this->effect = $gameObject->getEffect();
        $this->width = $width;
        $this->height = $height;

        /* Fetch the event IDs associated with the current GameObject */
        $eventByObjectRepository = $entityManager->getRepository(\App\Entity\EventByObject::class);
        /** @phpstan-ignore-next-line */
        $eventIDs = $eventByObjectRepository->findEventIDsByObjectIDAndLocation($gameObject->getId(), $roomID);

        /* Retrieve the corresponding events based on the fetched event IDs */
        $eventRepository = $entityManager->getRepository(\App\Entity\Event::class);
        $events = $eventRepository->findBy(['id' => $eventIDs]);

        /* Create an array of event_id/name pairs for the current GameObject */
        $eventOptions = [];
        foreach ($events as $event) {
            $eventOptions[$event->getId()] = $event->getName();
        }

        /* Set the event options for the current GameObject */
        foreach ($eventOptions as $eventID => $eventName) {
            $this->addOption($eventID, $eventName);
        }
    }

    

}
