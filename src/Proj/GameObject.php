<?php

namespace App\Proj;

class GameObject
{
    private $image;
    private $positionX;
    private $positionY;
    private $positionZ;
    private $clickable;
    private $name;
    private $options = [];
    private $objId;
    private $events = [];
    private $effect;

    public function __construct($objId, $image, $name, $positionX = 0, $positionY = 0, $positionZ = 0, $clickable = false, $options = [], $effect = null)
    {
        $this->objId = $objId;
        $this->image = $image;
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->clickable = $clickable;
        $this->name = $name;
        $this->options = $options;
        $this->events = [];
        $this->effect = $effect;
    }

    public function getObjId(): string
    {
        return $this->objId;
    }

    public function setObjId($objId)
    {
        $this->objId = $objId;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getEffect()
    {
        return $this->effect;
    }

    public function getEventById($eventId)
    {
        foreach ($this->events as $event) {
            if ($event->getEventId() == $eventId) {
                return $event;
            }
        }
        return null;
    }

    public function setEvents($events)
    {
        $this->events = $events;
    }

    public function addEvent($eventId)
    {
        if (!in_array($eventId, $this->events)) {
            $this->events[] = $eventId;
        }
    }

    public function removeEvent($eventId)
    {
        $index = array_search($eventId, $this->events);
        if ($index !== false) {
            unset($this->events[$index]);
        }
    }

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
    
    public function getOptions(): array
    {
        $options = [];
        foreach ($this->options as $optionKey => $eventId) {
            $options[$optionKey] = $eventId;
        }
        return $options;
    }
    
    
    public function getEventByOption($option)
    {
        return isset($this->options[$option]) ? $this->options[$option] : null;
    }
    
    public function addOption($key, $value)
    {
        $this->options[$key] = $value;
        error_log("Added option: key = $key, value = $value", 0);
    }
    
    
}
