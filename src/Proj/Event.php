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

       // @phpstan-ignore-next-line
    public function __construct(
        int $event_id,
        string $name,
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
}

