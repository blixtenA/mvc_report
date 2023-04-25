<?php

namespace App\Card;

class Game
{
    private int $round;
    private Player $player;
    private Player $bank;
    private DeckOfCards $deck;
    private bool $isPlayerTurn;
    private string $message = '';
    private Rules $rules;

    public function __construct($playerBet)
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

    public function startNewRound($playerBet): void 
    {
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

    public function getMessage()
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

    public function hit(): bool
    {
        if ($this->isPlayerTurn) {
            if ($this->player->hit($this->deck)) {
                return true;
            }
        } else {
            if ($this->bank->hit($this->deck)) {
                $this->message = "Bank takes a card";            
                return true;
            }
        }
        return false;
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
/*        $log_file = "./my-errors.log";
        error_log("class game over check \n", 3, $log_file);
*/
        $bankScore = $this->bank->getScore();
        $playerScore = $this->player->getScore();

//        error_log("bankScore". "$bankScore \n", 3, $log_file);
//        error_log("playerScore". "$playerScore \n", 3, $log_file);

        /* first exit condition - someone is fat. */
        if ($bankScore >= 21 || $playerScore >= 21) {
            return true;
        }
        return false;
    }

    public function endOfRound()
    {

        $result = $this->rules->determineWinner($this->player, $this->bank);
        if ($result[0] != null) {
            $payoutResult = $this->rules->payout($result, $this->player, $this->bank);
        } else {
            $payoutResult = true;
        }
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
            "anotherRound" => $payoutResult,
            "playerturn" => $this->isPlayerTurn,
        ];

        $this->resetBets();
        return $data;
    }
}
