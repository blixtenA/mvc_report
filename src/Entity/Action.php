<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
#[ORM\Table(name: '`action`')]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $event_action = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventAction(): ?string
    {
        return $this->event_action;
    }

    public function setEventAction(string $event_action): self
    {
        $this->event_action = $event_action;

        return $this;
    }
}
