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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(nullable: true)]
    private ?int $option_yes = null;

    #[ORM\Column(nullable: true)]
    private ?int $option_no = null;

    #[ORM\Column(nullable: true)]
    private ?int $option_object = null;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getOptionYes(): ?int
    {
        return $this->option_yes;
    }

    public function setOptionYes(?int $option_yes): self
    {
        $this->option_yes = $option_yes;

        return $this;
    }

    public function getOptionNo(): ?int
    {
        return $this->option_no;
    }

    public function setOptionNo(?int $option_no): self
    {
        $this->option_no = $option_no;

        return $this;
    }

    public function getOptionObject(): ?int
    {
        return $this->option_object;
    }

    public function setOptionObject(?int $option_object): self
    {
        $this->option_object = $option_object;

        return $this;
    }
}
