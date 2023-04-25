<?php

namespace App\Card;

class DeckOfCards
{
    private array $cards;
    private array $drawn;

    public function __construct()
    {
        $this->cards = [];
        $ranks = ['Ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King'];
        $families = ['Spades', 'Hearts', 'Diamonds', 'Clubs'];
        foreach ($families as $family) {
            foreach ($ranks as $rank) {
                $this->cards[] = new Card($rank, $family);
            }
        }
        $this->drawn = [];
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function drawCard(): object
    {
        if (count($this->cards) == 0) {
            return null;
        }
        $card = array_pop($this->cards);
        $this->drawn[] = $card;
        return $card;
    }

    public function mixCards(): void
    {
        $this->cards = array_merge($this->cards, $this->drawn);
        shuffle($this->cards);
        $this->drawn = [];
    }

    public function getDrawnCards(): array
    {
        return $this->drawn;
    }

    public function remainingCards(): int
    {
        return count($this->getCards());
    }

    public function sortRemainingCards(): void
    {
        usort($this->cards, function ($cardA, $cardB) {
            $suitOrder = ['Clubs', 'Diamonds', 'Hearts', 'Spades'];
            $aSuitIndex = array_search($cardA->getFamily(), $suitOrder);
            $bSuitIndex = array_search($cardB->getFamily(), $suitOrder);
        
            if ($aSuitIndex == $bSuitIndex) {
                $valueOrder = ['Ace','2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King'];
                $aValueIndex = array_search($cardA->getValue(), $valueOrder);
                $bValueIndex = array_search($cardB->getValue(), $valueOrder);
                return $aValueIndex - $bValueIndex;
            }
        
            return $aSuitIndex - $bSuitIndex;
        });        
    }

    public function getCardsAsString(): array
    {
        $cardsAsString = [];
        foreach ($this->cards as $card) {
            $cardsAsString[] = $card->getAsString();
        }
        return $cardsAsString;
    }

    /* New methods for 21 game. Do not mess with the code above this line.
    Seriously, no changes above. You shall not pass. */
}
