<?php

namespace App\Proj;

class Event
{
    private $event_id;
    private $text;
    private $name;
    private $actions;
    private $location;

    public function __construct(
        $event_id,
        $name,
        $text = "something happened",
        $location = "room",
        $actions = []
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

    public function getActions(): array
    {
        return $this->actions;
    }

    public function setActions(array $actions): void
    {
        $this->actions = $actions;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
}

