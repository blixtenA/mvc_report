<?php

namespace App\Card;

class Rules
{
    public function determineWinner($player, $bank): array
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
        } else {
            return [null, null, "Game ties, no winner!"];
        }
    }

    public function payout($result, Player $player, Player $bank)
    {
        $winner = $result[0];
        $loser = $result[1];
        $bet = intval($winner->getCurrentBet());

        $winner->addMoney($bet * 2);

        /* Check if either participant is at 0 money */
        if ($player->getMoney() == 0 || $bank->getMoney() == 0) {
            return false;
        }
        return true;
    }
}
