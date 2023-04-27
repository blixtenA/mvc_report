<?php

namespace App\Card;

class CardHand
{
    /**
    * @var Card[] $cards
    */
    private array $cards = [];
    private int $number;

    public function __construct(DeckOfCards $deck, int $number = 1)
    {
        $this->cards = [];
        $this->number = $number;
        for ($i = 0; $i < $this->number; $i++) {
            /** @var Card $card */
            $card = $deck->drawCard();
            if ($card !== null) {
                $this->cards[] = $card;
            }
        }
    }

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
        $this->number++;
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
    
            switch ($value) {
                case "Ace":
                    $score += ($score + 14 > 21) ? 1 : 14;
                    break;
                case "King":
                    $score += 13;
                    break;
                case "Queen":
                    $score += 12;
                    break;
                case "Jack":
                    $score += 11;
                    break;
                default:
                    $score += intval($value);
                    break;
            }
        }
    
        return $score;
    }

    public function isGameOver(string $participant = "player"): bool
    {
        if ($participant == "player") {
            return $this->getScore() >= 21;
        }
        return $this->getScore() >= 17;
    }
}
