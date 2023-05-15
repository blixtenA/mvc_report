<?php

namespace App\Proj;

class GameObject
{
    private $image;
    private $positionX;
    private $positionY;
    private $clickable;
    private $name;
    private $options;

    public function __construct($image, $positionX, $positionY, $name, $clickable = false, $options = [])
    {
        $this->image = $image;
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->clickable = $clickable;
        $this->name = $name;
        $this->options = $options;
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

    public function isClickable(): bool
    {
        return $this->clickable;
    }

    public function onClick()
    {
        $dialogue = "<div class='speechBubble'>";
        $dialogue .= "<p>{$this->name}</p>";
        foreach ($this->options as $option) {
            $dialogue .= "<button class='optionButton' data-option=\"{$option}\">{$option}</button>";
        }
        $dialogue .= "</div>";
        console.log("click");
        var_dump($this->options);
    
        return $dialogue;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
    
}
