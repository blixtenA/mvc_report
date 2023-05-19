<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $game_id = null;

    #[ORM\Column]
    private ?int $room_id = null;

    #[ORM\Column]
    private ?int $pos_x = null;

    #[ORM\Column]
    private ?int $pos_y = null;

    #[ORM\Column(nullable: true)]
    private ?bool $start = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameId(): ?int
    {
        return $this->game_id;
    }

    public function setGameId(int $game_id): self
    {
        $this->game_id = $game_id;

        return $this;
    }

    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id): self
    {
        $this->room_id = $room_id;

        return $this;
    }

    public function getPosX(): ?int
    {
        return $this->pos_x;
    }

    public function setPosX(int $pos_x): self
    {
        $this->pos_x = $pos_x;

        return $this;
    }

    public function getPosY(): ?int
    {
        return $this->pos_y;
    }

    public function setPosY(int $pos_y): self
    {
        $this->pos_y = $pos_y;

        return $this;
    }

    public function isStart(): ?bool
    {
        return $this->start;
    }

    public function setStart(?bool $start): self
    {
        $this->start = $start;

        return $this;
    }

}
