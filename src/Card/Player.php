<?php

namespace App\Card;

use Exception;

class Player
{
    private string $name;
    /**
    * @var CardHand
    */
    private object $hand;
    private int $score;
    private string $type;
    private int $endCond;
    private int $money;
    private int $currentBet;

    public function __construct(string $name, DeckOfCards $deck, int $currentBet = 10, string $type = "player")
    {
        $this->name = $name;
        $this->hand = new CardHand($deck, 2);
        $this->score = 0;

        /* Type init. Player and Bank have different end conditions. */
        $this->type = $type;
        $this->endCond = ($type === "player") ? 21 : 17;
        $this->score = $this->hand->getScore();

        /* init first bet and starting money, 100 */
        $this->money = 100;
        $this->currentBet = $currentBet;
        $this->money -= $this->currentBet;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEndCond(): int
    {
        return $this->endCond;
    }

    public function getHand(): CardHand
    {
        return $this->hand;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function reset(DeckOfCards $deck): void
    {
        $this->score = 0;
        $this->hand = new CardHand($deck, 2);
        $this->score = $this->hand->getScore();        
    }

    public function hit(DeckOfCards $deck): bool
    {
            /* Draw a card from the given deck and add to the hand */
        try {
            $card = $deck->drawCard();
        } catch (Exception $e) {
            /* No more cards in the deck */
            return false;
        }
          $this->hand->addCard($card);
    
        $this->score = $this->hand->getScore();
    
        /* Check if end condition applies */
        if ($this->hand->isGameOver($this->type)) {
            return false;
        }
        return true;
    }

    public function getMoney(): int
    {
        return $this->money;
    }

    public function addMoney(int $amount): int
    {
        $this->money += $amount;
        return $this->money;
    }

    public function payMoney(int $amount): int
    {
        $this->money -= $amount;
        return $this->money;
    }

    /* New bet method for Bank. Will bet a random even 10-number out of what is left. */
    public function newBet(): int
    {
        $this->currentBet = 10 * rand(1, intval($this->money / 10));
        $this->money -= $this->currentBet;
        return $this->currentBet;
    }

    public function setCurrentBet(int $amount): int
    {
        $this->currentBet = $amount;
        $this->money -= $this->currentBet;
        return $this->currentBet;
    }

    public function getCurrentBet(): int
    {
        return $this->currentBet;
    }
}
