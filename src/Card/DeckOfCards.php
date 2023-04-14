<?php

namespace App\Card;

class DeckOfCards
{
    private $cards;
    private $drawn;

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

    public function getCards()
    {
        return $this->cards;
    }

    public function drawCard()
    {
        if (count($this->cards) == 0) {
            return null;
        }
        $card = array_pop($this->cards);
        $this->drawn[] = $card;
        return $card;
    }

    public function mixCards()
    {
        $this->cards = array_merge($this->cards, $this->drawn);
        shuffle($this->cards);
        $this->drawn = [];
    }

    public function getDrawnCards()
    {
        return $this->drawn;
    }

    public function remainingCards()
    {
        return count($this->getCards());
    }

    public function sortRemainingCards()
    {
        usort($this->cards, function ($a, $b) {
            $suitOrder = ['Clubs', 'Diamonds', 'Hearts', 'Spades'];
            $aSuitIndex = array_search($a->getFamily(), $suitOrder);
            $bSuitIndex = array_search($b->getFamily(), $suitOrder);

            if ($aSuitIndex == $bSuitIndex) {
                $valueOrder = ['Ace','2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King'];
                $aValueIndex = array_search($a->getValue(), $valueOrder);
                $bValueIndex = array_search($b->getValue(), $valueOrder);
                return $aValueIndex - $bValueIndex;
            }

            return $aSuitIndex - $bSuitIndex;
        });
    }

    public function getCardsAsString()
    {
        $cardsAsString = [];
        foreach ($this->cards as $card) {
            $cardsAsString[] = $card->getAsString();
        }
        return $cardsAsString;
    }
}
