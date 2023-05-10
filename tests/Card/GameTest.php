<?php

namespace App\Card;

use App\Card\Player;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    private Game $game;

    protected function setUp(): void
    {
        /* init a default game with starting bet 10 */
        $this->game = new Game(10);
    }

    public function testStartNewRound(): void
    {
        $game = new Game(10);
        $player = $game->getPlayer();
        $bank = $game->getBank();
        $game->startNewRound(10);
        $this->assertEquals(80, $game->getPlayer()->getMoney());
        $this->assertTrue($game->isPlayerTurn());
    }

    public function testGetPlayer(): void
    {
        $game = new Game(10);
        $this->assertInstanceOf(Player::class, $game->getPlayer());
    }

    public function testGetBank(): void
    {
        $game = new Game(10);
        $this->assertInstanceOf(Player::class, $game->getBank());
    }

    public function testGetDeck(): void
    {
        $game = new Game(10);
        $this->assertInstanceOf(DeckOfCards::class, $game->getDeck());
    }

    public function testIsBankTurn(): void
    {
        $this->game->stand();
        $this->assertFalse($this->game->isPlayerTurn());
    }

    public function testResetHard(): void
    {
        $game = new Game(10);
        $player = $game->getPlayer();
        $deck = $game->getDeck();
        $player->hit($deck);
        $game->resetHard();
        $hand = $player->getHand();
        $this->assertCount(2, $hand->getCards());
    }

    /* Hit the player */
    public function testPlayerHitScore(): void
    {
        $player = $this->game->getPlayer();
        $playerscore = $player->getScore();
        $this->game->hit();

        $this->assertTrue($player->getScore() > $playerscore);
    }

    /* Hit the bank */
    public function testBankHitScore(): void
    {
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();

        } while ($player->getScore() >= 21 || $bank->getScore()  >= 17);

        $bankscore = $bank->getScore();
        $game->stand();
        $game->hit();

        $this->assertTrue($bank->getScore() > $bankscore);
    }

    /* Bank has 17, that meets the end condition for the bank and it quite. */
    public function testBankHitScorePartDeux(): void
    {
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();

        } while ($player->getScore() >= 21 || $bank->getScore()  != 17);

        $bankScore = $bank->getScore();
        $game->stand();

        $this->assertFalse($game->hit());
    }

    /* Bank has a low score and hits. */
    public function testBankHitScoreLowScore(): void
    {
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();

        } while ($player->getScore() >= 21 || $bank->getScore()  > 7);

        $bankScore = $bank->getScore();
        $game->stand();

        $this->assertTrue($game->hit());
    }

    public function testCheckGameOver(): void
    {
        $game = new Game(10);
        $player = $game->getPlayer();

        /* Loop until player has a hand with a score of at least 21 */
        while ($player->getScore() < 21) {
            $game = new Game(10);
            $player = $game->getPlayer();
        }

        /* Assert that checkGameOver returns true */
        $this->assertTrue($game->checkGameOver());
    }

    public function testCheckGameOverNoCards(): void
    {

        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();

        } while ($player->getScore() >= 21 || $bank->getScore()  >= 17);

        for ($i = 0; $i < 48; $i++) {
            $game->getDeck()->drawCard();
        }

        /* Assert that checkGameOver returns true */
        $this->assertTrue($game->checkGameOver());
    }

    public function testCheckGameOverFalse(): void
    {

        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();

        } while ($player->getScore() >= 21 || $bank->getScore()  >= 17);

        /* Assert that checkGameOver returns true */
        $this->assertFalse($game->checkGameOver());
    }

    public function testGetMessage(): void
    {
        $game = new Game(10);
        $message = $game->getMessage();
        $this->assertEmpty($message);
    }

    public function testStand(): void
    {
        $this->game->stand();
        $this->assertFalse($this->game->isPlayerTurn());
    }

    public function testResetBets(): void
    {
        $this->game->resetBets();
        $this->assertEquals(0, $this->game->getData()['playerbet']);
        $this->assertEquals(0, $this->game->getData()['bankbet']);
    }

    public function testGetData(): void
    {
        $game = new Game(10);
        $data = $game->getData();

        $this->assertEquals(1, $data['round']);
        $this->assertCount(2, $data['playerhand']);
        $this->assertCount(2, $data['bankhand']);
        $this->assertIsInt($data['playerscore']);
        $this->assertIsInt($data['bankscore']);
        $this->assertEquals(90, $data['playermoney']);
        $this->assertIsInt($data['bankmoney']);
        $this->assertEquals(10, $data['playerbet']);
        $this->assertIsInt($data['bankbet']);
        $this->assertEquals(48, $data['remaining']);
        $this->assertTrue($data['playerturn']);
        $this->assertEmpty($data['message']);
    }

    public function testEndOfRound(): void
    {
        /* Keep starting a new game until we have a workable scenario */
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();
        } while ($player->getScore() < 17 || $player->getScore() > 21 || $bank->getScore() < 17 || $bank->getScore() > 21 || $bank->getScore() >= $player->getScore());

        /* End the round and get the data */
        $data = $game->endOfRound();

        /* Assert that the gameover key is of type bool */
        $this->assertIsString($data['gameover']);

        /* Assert that the remaining key is of type int */
        $this->assertIsInt($data['remaining']);

        /* Assert that the playerhand and bankhand keys are arrays */
        $this->assertIsArray($data['playerhand']);
        $this->assertIsArray($data['bankhand']);

        /* Assert that the playerscore and bankscore keys are of type int */
        $this->assertIsInt($data['playerscore']);
        $this->assertIsInt($data['bankscore']);

        /* Assert that the playermoney and bankmoney keys are of type int or float */
        $this->assertIsNumeric($data['playermoney']);
        $this->assertIsNumeric($data['bankmoney']);

        /* Assert that the playerbet and bankbet keys are of type int or float */
        $this->assertIsNumeric($data['playerbet']);
        $this->assertIsNumeric($data['bankbet']);

        /* Assert that the round and anotherRound keys are of type int and bool */
        $this->assertIsInt($data['round']);
        $this->assertIsBool($data['anotherRound']);

        /* Assert that the playerturn key is of type bool */
        $this->assertIsBool($data['playerturn']);
    }




}
