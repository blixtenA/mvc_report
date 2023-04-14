<?php

namespace App\Card;

class CardHand
{
    private $cards;

    public function __construct(DeckOfCards $deck, $number = 1)
    {
        $this->cards = [];
        for ($i = 0; $i < $number; $i++) {
            $card = $deck->drawCard();
            if ($card) {
                $this->cards[] = $card;
            }
        }
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function addCard(Card $card)
    {
        if (count($this->cards) < $number) {
            $this->cards[] = $card;
            return true;
        } else {
            return false;
        }
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
