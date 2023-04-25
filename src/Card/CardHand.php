<?php

namespace App\Card;

class CardHand
{
    private array $cards;
    private int $number;

    public function __construct(DeckOfCards $deck, $number = 1)
    {
        $this->cards = [];
        $this->number = $number;
        for ($i = 0; $i < $this->number; $i++) {
            $card = $deck->drawCard();
            if ($card) {
                $this->cards[] = $card;
            }
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
        $this->number++;
    }    

    public function getCardsAsString(): array
    {
        $cardsAsString = [];
        foreach ($this->cards as $card) {
            $cardsAsString[] = $card->getAsString();
        }
        return $cardsAsString;
    }

    public function hasAce(): bool
    {
        foreach ($this->cards as $card) {
            if ($card->getValue() === 'Ace') {
                return true;
            }
        }
        return false;
    }

    public function getScore(): int
    {
        $score = 0;

        foreach ($this->cards as $card) {
            $value = $card->getValue();

            if ($value === "Ace") {
                if ($score + 14 > 21) {
                    $score += 1;
                } else {
                    $score += 14;
                }
            } elseif ($value === "King") {
                $score += 13;
            } elseif ($value === "Queen") {
                $score += 12;
            } elseif ($value === "Jack") {
                $score += 11;
            } else {
                $score += intval($value);
            }
        }

        return $score;
    }

    public function isGameOver($participant = "player")
    {
        if ($participant == "player") {
            return $this->getScore() >= 21;
        } else {
            return $this->getScore() >= 17;
        }
    }
}
