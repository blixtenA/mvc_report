<?php

namespace App\Card;

class Card
{
    private string $value;
    private string $family;

    /* Constructor, default = create a random card */
    public function __construct(string $value = null, string $family = null)
    {
        if ($value !== null && $family !== null) {
            $this->value = $value;
            $this->family = $family;
            return;
        }
    
        $values = ['Ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King'];
        $families = ['Spades', 'Hearts', 'Diamonds', 'Clubs'];
    
        $this->value = $values[array_rand($values)];
        $this->family = $families[array_rand($families)];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getFamily(): string
    {
        return $this->family;
    }

    public function getAsString(): string
    {
        return "{$this->value} of {$this->family}";
    }

    public function getSymbol(): string
    {
        $symbols = [
            'Spades' => ['Ace' => '🂡', '2' => '🂢', '3' => '🂣', '4' => '🂤', '5' => '🂥', '6' => '🂦', '7' => '🂧', '8' => '🂨', '9' => '🂩', '10' => '🂪', 'Jack' => '🂫', 'Queen' => '🂭', 'King' => '🂮'],
            'Clubs' => ['Ace' => '🃑', '2' => '🃒', '3' => '🃓', '4' => '🃔', '5' => '🃕', '6' => '🃖', '7' => '🃗', '8' => '🃘', '9' => '🃙', '10' => '🃚', 'Jack' => '🃛', 'Queen' => '🃝', 'King' => '🃞'],
            'Hearts' => ['Ace' => '🂱', '2' => '🂲', '3' => '🂳', '4' => '🂴', '5' => '🂵', '6' => '🂶', '7' => '🂷', '8' => '🂸', '9' => '🂹', '10' => '🂺', 'Jack' => '🂻', 'Queen' => '🂽', 'King' => '🂾'],
            'Diamonds' => ['Ace' => '🃁', '2' => '🃂', '3' => '🃃', '4' => '🃄', '5' => '🃅', '6' => '🃆', '7' => '🃇', '8' => '🃈', '9' => '🃉', '10' => '🃊', 'Jack' => '🃋', 'Queen' => '🃍', 'King' => '🃎']
        ];

        return $symbols[$this->family][$this->value];
    }
}
