<?php

namespace App\Card;

class CardHand
{
    /**
    * @var Card[] $cards
    */
    private array $cards = [];
    private int $number;

    /**
     * CardHand constructor.
     *
     * @param DeckOfCards $deck
     * @param int         $number
     */
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
     * Get the cards in the hand.
     *
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Add a card to the hand.
     *
     * @param Card $card
     */
    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
        $this->number++;
    }

    /**
     * Get the cards in the hand as strings.
     *
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

    /**
     * Check if the hand has an ace.
     *
     * @return bool
     */
    public function hasAce(): bool
    {
        foreach ($this->cards as $card) {
            if ($card->getValue() === 'Ace') {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the score of the hand.
     *
     * @return int
     */
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

    /**
     * Check if the game is over for the participant.
     *
     * @param string $participant
     *
     * @return bool
     */
    public function isGameOver(string $participant = "player"): bool
    {
        if ($participant == "player") {
            return $this->getScore() >= 21;
        }
        return $this->getScore() >= 17;
    }
}
