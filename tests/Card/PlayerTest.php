<?php

namespace App\Card;

use App\Card\Player;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
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

    public function testInitWithBet(): void
    {
        $this->assertInstanceOf(Player::class, $this->player);
        $this->assertEquals(20, $this->player->getCurrentBet());
    }

    public function testInitWithoutBet(): void
    {
        $player = new Player('Player', $this->deck);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals(10, $player->getCurrentBet());
    }

    public function testInitialMoney(): void
    {
        $this->assertEquals(80, $this->player->getMoney());
    }

    public function testGetName(): void
    {
        $this->assertEquals('Player', $this->player->getName());
    }

    public function testGetEndCond(): void
    {
        $this->assertEquals(21, $this->player->getEndCond());
        $this->assertEquals(17, $this->bank->getEndCond());
    }

    public function testGetType(): void
    {
        $this->assertEquals('player', $this->player->getType());
    }

    public function testGetHand(): void
    {
        $this->assertInstanceOf(CardHand::class, $this->player->getHand());
    }

    public function testGetScore(): void
    {
        $this->assertIsInt($this->player->getScore());
    }

    public function testResetHand(): void
    {
        $originalHand = $this->player->getHand();
        $this->player->reset($this->deck);
        $newHand = $this->player->getHand();

        $this->assertNotEquals($originalHand, $newHand);
    }

    public function testHit(): void
    {
        $originalHand = serialize($this->player->getHand());
        $this->player->hit($this->deck);
        $newHand = serialize($this->player->getHand());

        $this->assertNotEquals($originalHand, $newHand);
    }

    public function testGetMoney(): void
    {
        $money = $this->player->getMoney();
        $this->assertEquals(80, $money);
    }

    public function testAddMoney(): void
    {
        $money = $this->player->addMoney(50);
        $this->assertEquals(130, $money);
    }

    public function testPayMoney(): void
    {
        $money = $this->player->payMoney(50);
        $this->assertEquals(30, $money);
    }

    public function testNewBetForBank(): void
    {
        $bank = new Player('Bank', $this->deck, 10, 'bank');
        $currentMoney = $bank->getMoney();
        $bet = $bank->newBet();

        /* Check that the bet is a multiple of 10 */
        $this->assertEquals(0, $bet % 10);

        /* Check that the bet is less than or equal to the bank's available budget */
        $this->assertLessThanOrEqual($currentMoney, $bet);

        /* Check that the bank's money is reduced by the bet amount */
        $this->assertEquals($bank->getMoney(), $currentMoney - $bet);
    }

    public function testSetCurrentBet(): void
    {
        $this->player->setCurrentBet(20);
        $this->assertEquals(20, $this->player->getCurrentBet());
        $this->assertEquals(60, $this->player->getMoney());
    }

    public function testGetCurrentBet(): void
    {
        $this->player->setCurrentBet(20);
        $this->assertEquals(20, $this->player->getCurrentBet());
    }

    public function testHitException(): void
    {
        $game = new Game(10);

        for ($i = 0; $i < 48; $i++) {
            $game->getDeck()->drawCard();
        }

        $player = $game->getPlayer();

        $this->assertFalse($player->hit($game->getDeck()));

    }

    public function testHitTrue(): void
    {
        do {
            $game = new Game(10);
            $player = $game->getPlayer();
            $bank = $game->getBank();
        } while ($player->getScore() > 7 || $bank->getScore() > 17);

        $this->assertTrue($player->hit($game->getDeck()));

    }

}
