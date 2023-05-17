<?php

namespace App\Entity;

use App\Repository\GameObjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameObjectRepository::class)]
class GameObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $positionX = null;

    #[ORM\Column]
    private ?int $positionY = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $clickable = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $options = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPositionX(): ?int
    {
        return $this->positionX;
    }

    public function setPositionX(int $positionX): self
    {
        $this->positionX = $positionX;

        return $this;
    }

    public function getPositionY(): ?int
    {
        return $this->positionY;
    }

    public function setPositionY(int $positionY): self
    {
        $this->positionY = $positionY;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isClickable(): ?bool
    {
        return $this->clickable;
    }

    public function setClickable(bool $clickable): self
    {
        $this->clickable = $clickable;

        return $this;
    }

    public function getOptions(): ?array
    {
        return json_decode($this->options, true) ?: [];
    }

    public function setOptions(?string $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function getOptionsArray(): array
    {
        error_log("getOptionsArray",0);
        $optionsString = $this->options;
        $optionsArray = [];
    
        // Remove curly braces and double quotes from the string
        $optionsString = str_replace(['{', '}', '"'], '', $optionsString);
        error_log("optionsString ".$optionsString,0);
    
        // Split the string into key-value pairs
        $pairs = explode(',', $optionsString);
    
        foreach ($pairs as $pair) {
            // Split each pair into key and value
            [$key, $value] = explode(':', $pair);
    
            // Remove leading/trailing spaces and add the pair to the options array
            $optionsArray[trim($key)] = trim($value);
        }
    
        return $optionsArray;
    }

    public function setOptionsArray(array $options): self
    {
        $this->options = json_encode($options);
        return $this;
    }

}
