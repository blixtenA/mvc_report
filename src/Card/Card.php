<?php

namespace App\Card;

class Card
{
    /**
     * @var string The card value
     */
    private string $value;

    /**
     * @var string The card family
     */
    private string $family;

    /**
     * Card constructor.
     * @param string|null $value The card value. Default = a random value.
     * @param string|null $family The card family. Default = a random family.
     */
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

    /**
     * Returns the card value.
     *
     * @return string The card value.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the card family.
     *
     * @return string The card family.
     */
    public function getFamily(): string
    {
        return $this->family;
    }


    /**
     * Returns the card as a string in the format "{value} of {family}".
     *
     * @return string The card as a string.
     */
    public function getAsString(): string
    {
        return "{$this->value} of {$this->family}";
    }

    /**
     * Returns the card symbol as a string, using Unicode playing card symbols.
     *
     * @return string The card symbol.
     */
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
