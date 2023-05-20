<?php

namespace App\Proj;

class GameObject
{
    private string $image;
    private int $positionX;
    private int $positionY;
    private ?int $positionZ = 0;
    private bool $clickable;
    private string $name;
    /**
     * @var array<string, mixed>
     */
    private ?array $options = [];
    private int $objId;
//    private array $events = [];
    private ?string $effect;

    // @phpstan-ignore-next-line
    public function __construct(
        int $objId,
        string $image,
        string $name,
        int $positionX = 0,
        int $positionY = 0,
        int $positionZ = 0,
        bool $clickable = false,
        ?array $options = [],
        ?string $effect = null
    ) {
        $this->objId = $objId;
        $this->image = $image;
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->positionZ = $positionZ;
        $this->clickable = $clickable;
        $this->name = $name;
        $this->options = $options;
//        $this->events = [];
        $this->effect = $effect;
    }

    public function getObjId(): int 
    {
        return $this->objId;
    }

    public function setObjId(int $objId): void 
    {
        $this->objId = $objId;
    }

/*    public function getEvents(): array
    {
        return $this->events;
    } */

    public function getEffect(): ?string
    {
        return $this->effect;
    }

/*    public function getEventById(int $eventId): ?int
    {
        foreach ($this->events as $event) {
            if ($event->getEventId() == $eventId) {
                return $event;
            }
        }
        return null;
    }

    /**
     * @param int[] $events
     */
/*    public function setEvents(array $events): void
    {
        $this->events = $events;
    }

    public function addEvent(int $eventId): void
    {
        if (!in_array($eventId, $this->events)) {
            $this->events[] = $eventId;
        }
    }

    public function removeEvent(int $eventId): void
    {
        $index = array_search($eventId, $this->events);
        if ($index !== false) {
            unset($this->events[$index]);
        }
    }
*/
    public function getImage(): string
    {
        return $this->image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPositionX(): int
    {
        return $this->positionX;
    }

    public function getPositionY(): int
    {
        return $this->positionY;
    }

    public function getPositionZ(): int
    {
        return $this->positionY;
    }

    public function isClickable(): bool
    {
        return $this->clickable;
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
 * @return array<string, mixed>
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
    
    public function addOption(string $key, mixed $value): void
    {
        $this->options[$key] = $value;
        error_log("Added option: key = $key, value = $value", 0);
    }
    
    
}
