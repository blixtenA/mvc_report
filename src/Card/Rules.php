<?php

namespace App\Card;
use App\Entity\Player;

class Rules
{
    /**
    * @param Player $player
    * @param Player $bank
    * @return array{Player|null, Player|null, string}
    */
    public function determineWinner(Player $player, Player $bank): array
    {
        $playerScore = $player->getScore();
        $bankScore = $bank->getScore();

        /* Return [winner, loser, message] */
        if ($playerScore > 21) {
            return [$bank, $player, "Bank wins, Player is fat!"];
        } elseif ($bankScore > 21) {
            return [$player, $bank, "Player wins, Bank is fat!"];
        } elseif ($playerScore > $bankScore) {
            return [$player, $bank, "Player wins on score!"];
        } elseif ($bankScore > $playerScore) {
            return [$bank, $player, "Bank wins on score!"];
        }
        return [null, null, "Game ties, no winner!"];
    }

    /**
     * @param array $result
     * @param Player $player
     * @param Player $bank
     * @return bool
     */
    public function payout(array $result, Player $player, Player $bank): bool
    {
        $winner = $result[0];
        $bet = intval($winner->getCurrentBet());

        $winner->addMoney($bet * 2);

        /* Check if either participant is at 0 money */
        if ($player->getMoney() == 0 || $bank->getMoney() == 0) {
            return false;
        }
        return true;
    }

    public function endOfGame(Game $game): bool
    {
        $deck = $game->getDeck();

        if ($deck->remainingCards() < 4) {
            return true;
        }

        return false;
    }
}
