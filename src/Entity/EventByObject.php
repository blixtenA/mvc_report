<?php

namespace App\Entity;

use App\Repository\EventByObjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventByObjectRepository::class)]
class EventByObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $object_id = null;

    #[ORM\Column]
    private ?int $event_id = null;

    #[ORM\Column]
    private ?int $location = null;

    #[ORM\Column(nullable: true)]
    private ?int $action1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $action2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $action3 = null;

    #[ORM\Column(nullable: true)]
    private ?int $action4 = null;

    #[ORM\Column(nullable: true)]
    private ?int $action5 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectId(): ?int
    {
        return $this->object_id;
    }

    public function setObjectId(int $object_id): self
    {
        $this->object_id = $object_id;

        return $this;
    }

    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    public function setEventId(int $event_id): self
    {
        $this->event_id = $event_id;

        return $this;
    }

    public function getLocation(): ?int
    {
        return $this->location;
    }

    public function setLocation(int $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getAction1(): ?int
    {
        return $this->action1;
    }

    public function setAction1(?int $action1): self
    {
        $this->action1 = $action1;

        return $this;
    }

    public function getAction2(): ?int
    {
        return $this->action2;
    }

    public function setAction2(?int $action2): self
    {
        $this->action2 = $action2;

        return $this;
    }

    public function getAction3(): ?int
    {
        return $this->action3;
    }

    public function setAction3(?int $action3): self
    {
        $this->action3 = $action3;

        return $this;
    }

    public function getAction4(): ?int
    {
        return $this->action4;
    }

    public function setAction4(?int $action4): self
    {
        $this->action4 = $action4;

        return $this;
    }

    public function getAction5(): ?int
    {
        return $this->action5;
    }

    public function setAction5(?int $action5): self
    {
        $this->action5 = $action5;

        return $this;
    }
}
