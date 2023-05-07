<?php

namespace App\Card;

use App\Card\Player;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class RulesTest extends TestCase
{
    private Player $player;
    private Player $bank;
    private DeckOfCards $deck;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deck = new DeckOfCards();
        $this->player = new Player('Player', $this->deck, 20, 'player');
        $this->bank = new Player('Bank', $this->deck, 50, 'bank');
    }

    public function testDetermineWinnerPlayerWins(): void
    {
        /* Keep starting a new game until we have a workable scenario */
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();
        } while (($player->getScore() < 17 || $player->getScore() > 21 || $bank->getScore() < 17 || $bank->getScore() > 21 || $bank->getScore() >= $player->getScore()));

        $rules = new Rules();
        $result = $rules->determineWinner($player, $bank);

        $this->assertSame($player, $result[0]);
        $this->assertSame($bank, $result[1]);
        $this->assertSame("Player wins on score!", $result[2]);
    }

    public function testDetermineWinnerBankWinsPlayerIsFat(): void
    {
        /* Keep starting a new game until we have a workable scenario */
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();
        } while ($player->getScore() <= 21 && $bank->getScore() <= 21 && $bank->getScore() >= $player->getScore());

        $rules = new Rules();
        $result = $rules->determineWinner($this->player, $this->bank);

        $this->assertSame($this->player, $result[1]);
        $this->assertSame($this->bank, $result[0]);
        $this->assertSame("Bank wins, Player is fat!", $result[2]);
    }

    public function testDetermineWinnerPlayerWinsBankIsFat(): void
    {
        /* Keep starting a new game until we have a workable scenario */
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();
        } while (!($bank->getScore() > 21 && $player->getScore() <= 21));

        $rules = new Rules();
        $result = $rules->determineWinner($player, $bank);

        $this->assertSame($player, $result[0]);
        $this->assertSame($bank, $result[1]);
        $this->assertSame("Player wins, Bank is fat!", $result[2]);
    }

    public function testDetermineWinnerBankWinsOnScore(): void
    {
        /* Keep starting a new game until we have a workable scenario */
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();
        } while (($player->getScore() < 17 || $player->getScore() > 21 || $bank->getScore() < 17 || $bank->getScore() > 21 || $bank->getScore() <= $player->getScore()));

        $rules = new Rules();
        $result = $rules->determineWinner($player, $bank);

        $this->assertSame($bank, $result[0]);
        $this->assertSame($player, $result[1]);
        $this->assertSame("Bank wins on score!", $result[2]);
    }

    public function testDetermineWinnerGameTies(): void
    {
        /* Keep starting a new game until we have a workable scenario */
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();
        } while (($player->getScore() < 17 || $player->getScore() > 21 || $bank->getScore() < 17 || $bank->getScore() > 21 || $bank->getScore() != $player->getScore()));

        $rules = new Rules();
        $result = $rules->determineWinner($player, $bank);

        $this->assertNull($result[0]);
        $this->assertNull($result[1]);
        $this->assertSame("Game ties, no winner!", $result[2]);
    }

    public function testPayoutPlayerWins(): void
    {
        $game = new Game(10);
        $player = $game->getPlayer();
        $bank = $game->getBank();

        // Hardcode the payout array to test the payout method
        $result = [$player, $bank, "Player wins on score!"];

        $rules = new Rules();
        $rules->payout($result, $player, $bank);

        $this->assertEquals(110, $player->getMoney());
    }

    public function testPayoutBankWinsAndPlayerRunsOutOfMoney(): void
    {
        $game = new Game(100);
        $player = $game->getPlayer();
        $bank = $game->getBank();

        // Hardcode the payout array to test the payout method
        $result = [$bank, $player, "Bank wins on score!"];

        $rules = new Rules();
        $payoutResult = $rules->payout($result, $player, $bank);

        $this->assertFalse($payoutResult);
    }

    public function testEndOfGameNotReached(): void
    {
        $game = new Game(10);
        $rules = new Rules();
        $this->assertFalse($rules->endOfGame($game));
    }

    public function testEndOfGame(): void
    {
        $game = new Game(10);
        $deck = $game->getDeck();

        // Draw cards until there are only 3 cards left in the deck
        while ($deck->remainingCards() > 3) {
            $deck->drawCard();
        }

        $rules = new Rules();
        $result = $rules->endOfGame($game);

        $this->assertTrue($result);
    }



}
