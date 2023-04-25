<?php

namespace App\Card;

class Player
{
    private string $name;
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
        if ($type === "player") {
            $this->endCond = 21;
        } else {
            $this->endCond = 17;
        }
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

    public function getHand(): object
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

    public function reset($deck): void
    {
        $this->score = 0;
        $this->hand = new CardHand($deck, 2);
    }

    public function hit($deck): bool
    {
        /* Draw a card from the given deck and add to the hand */
        $card = $deck->drawCard();
        $this->hand->addCard($card);

        $this->score = $this->hand->getScore();

        /* Check if end condition applies */
        if ($this->hand->isGameOver($this->type)) {
            return false;
        } else {
            return true;
        }
    }

    public function getMoney(): int
    {
        return $this->money;
    }

    public function addMoney($amount): int
    {
        $this->money += $amount;
        return $this->money;
    }

    public function payMoney($amount): int
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

    public function setCurrentBet($amount): int
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
