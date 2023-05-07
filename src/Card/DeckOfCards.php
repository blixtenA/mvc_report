<?php

namespace App\Card;

use Exception;

class DeckOfCards
{
    /**
    * @var Card[] $cards
    */
    private array $cards;
    /**
    * @var Card[]
    */
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

    /**
    * @return Card[]
    */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
    * @return Card
    * @throws Exception
    */
    public function drawCard(): Card
    {
        if (count($this->cards) == 0) {
            throw new Exception("No more cards in the deck");
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

    /**
    * @return Card[]
    */
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

    /**
    * @return string[]
    */
    public function getCardsAsString(): array
    {
        $cardsAsString = [];
        foreach ($this->cards as $card) {
            $cardsAsString[] = $card->getAsString();
        }
        return $cardsAsString;
    }
}
