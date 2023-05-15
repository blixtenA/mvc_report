<?php

namespace App\Card;

use App\Card\DeckOfCards;
use App\Card\CardHand;

class Game
{
    private int $round;
    private Player $player;
    private Player $bank;
    private DeckOfCards $deck;
    private bool $isPlayerTurn;
    private string $message = '';
    private Rules $rules;

    public function __construct(int $playerBet)
    {
        $this->round = 1;
        $this->deck = new DeckOfCards();
        $this->isPlayerTurn = true;
        $this->rules = new Rules();

        /* Shuffle the deck */
        $this->deck->mixCards();

        /* Init the bank and player */
        $this->bank = new Player("bank", $this->deck, 10, "bank");
        $this->player = new Player("player", $this->deck, $playerBet);
    }

    public function startNewRound(int $playerBet): void
    {
        $this->message = "";
        $bank = $this->bank;
        $player = $this->player;

        /* Reset the Bank and Player objects */
        $bank->reset($this->deck);
        $player->reset($this->deck);

        /* Set the players' current bets and the player turn */
        $bank->newBet();
        $player->setCurrentBet($playerBet);
        $this->isPlayerTurn = true;
    }

    public function isPlayerTurn(): bool
    {
        return $this->isPlayerTurn;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getBank(): Player
    {
        return $this->bank;
    }

    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    public function getMessage(): string
    {
        $message = $this->message;
        $this->message = '';
        return $message;
    }

    public function resetHard(): void
    {
        $this->round++;
        $this->player->reset($this->deck);
        $this->bank->reset($this->deck);
    }

    /**
     * Play a turn.
     * 
     * */
    public function hit(): bool
    {
        if ($this->isPlayerTurn) {
            return $this->hitPlayer();
        } else {
            return $this->hitBank();
        }
    }

    /* Player's turn, hit */
    private function hitPlayer(): bool
    {
        if ($this->player->hit($this->deck)) {
            return true;
        }
        return false;
    }

    /* Bank's turn, hit or stand */
    private function hitBank(): bool
    {
        $score = $this->bank->getScore();
        $endCond = $this->bank->getEndCond();
        while ($score < $endCond && $this->bank->hit($this->deck)) {
            $score = $this->bank->getScore();
        }
        if ($score >= $endCond) {
            $this->message = "Bank stands";
            return false;
        }
        $this->message = "Bank takes a card";
        return true;
    }

    public function stand(): void
    {
        $this->isPlayerTurn = false;
    }

    public function resetBets(): void
    {
        $this->bank->setCurrentBet(0);
        $this->player->setCurrentBet(0);
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        $playerhand = $this->player->getHand();
        $bankhand = $this->bank->getHand();

        return [
            "round" => $this->round,
            "playerhand" => $playerhand->getCards(),
            "bankhand" => $bankhand->getCards(),
            "playerscore" => $playerhand->getScore(),
            "bankscore" => $bankhand->getScore(),
            "playermoney" => $this->player->getMoney(),
            "bankmoney" => $this->bank->getMoney(),
            "playerbet" => $this->player->getCurrentBet(),
            "bankbet" => $this->bank->getCurrentBet(),
            "remaining" => $this->deck->remainingCards(),
            "playerturn" => $this->isPlayerTurn,
            "message" => $this->getMessage()
        ];
    }

    public function checkGameOver(): bool
    {
        $bankScore = $this->bank->getScore();
        $playerScore = $this->player->getScore();

        /* first exit condition - someone is fat. */
        if ($bankScore >= 21 || $playerScore >= 21) {
            return true;
        }
        /* Second exit condition - deck has run out of cards */
        if ($this->deck->remainingCards() == 0) {
            return true;
        }
        return false;
    }

    /**
    * @return array<string, mixed>
    */
    public function endOfRound(): array
    {
        $anotherRound = true;

        $result = $this->rules->determineWinner($this->player, $this->bank);
        if ($result[0] != null) {
            $anotherRound = $this->rules->payout($result, $this->player, $this->bank);
        }

        if ($this->rules->endOfGame($this)) {
            $anotherRound = false;
        }

        /* Check endOfGame here */

        $playerhand = $this->player->getHand();
        $bankhand = $this->bank->getHand();

        $data = [
            "round" => $this->round,
            "gameover" => $result[2],
            "playerhand" => $playerhand->getCards(),
            "bankhand" => $bankhand->getCards(),
            "playerscore" => $playerhand->getScore(),
            "bankscore" => $bankhand->getScore(),
            "playermoney" => $this->player->getMoney(),
            "bankmoney" => $this->bank->getMoney(),
            "playerbet" => $this->player->getCurrentBet(),
            "bankbet" => $this->bank->getCurrentBet(),
            "remaining" => $this->deck->remainingCards(),
            "anotherRound" => $anotherRound,
            "playerturn" => $this->isPlayerTurn,
        ];

        $this->resetBets();
        return $data;
    }
}
