<?php

namespace App\Card;

use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    private DeckOfCards $deck;

    protected function setUp(): void
    {
        $this->deck = new DeckOfCards();
    }

    public function testDeckOfCardsCreation(): void
    {
        $this->assertCount(52, $this->deck->getCards());
        $this->assertCount(0, $this->deck->getDrawnCards());
    }

    public function testDrawCard(): void
    {
        $card = $this->deck->drawCard();
        $this->assertInstanceOf(Card::class, $card);
        $this->assertCount(51, $this->deck->getCards());
        $this->assertCount(1, $this->deck->getDrawnCards());
    }

    public function testDrawAllCards(): void
    {
        while ($this->deck->remainingCards() > 0) {
            $this->deck->drawCard();
        }
        $this->expectException('Exception');
        $this->deck->drawCard();
    }

    public function testMixCards(): void
    {
        /* draw two cards */
        $this->deck->drawCard();
        $this->deck->drawCard();
        /* mix */
        $this->deck->mixCards();
        /* now we have 52 in the cards again... */
        $this->assertCount(52, $this->deck->getCards());
        /* and 0 drawn */
        $this->assertCount(0, $this->deck->getDrawnCards());
    }

    public function testRemainingCards(): void
    {
        /* 52 before */
        $this->assertEquals(52, $this->deck->remainingCards());
        /* 52 - 1 */
        $this->deck->drawCard();
        /* = 51 */
        $this->assertEquals(51, $this->deck->remainingCards());
    }

    public function testSortRemainingCards(): void
    {
        $this->deck->drawCard();
        $this->deck->drawCard();
        $this->deck->sortRemainingCards();
        $cards = $this->deck->getCards();
        $this->assertLessThan(0, strcmp($cards[0]->getAsString(), $cards[count($cards) - 1]->getAsString()));
    }

    public function testMixCardsDiffer(): void
    {
        /* Get the starting setup of the deck */
        $cardsBeforeMixing = $this->deck->getCards();
        /* Mix */
        $this->deck->mixCards();
        /* Compare */
        $cardsAfterMixing = $this->deck->getCards();
        $this->assertNotEquals($cardsBeforeMixing, $cardsAfterMixing);
    }

    public function testConstructorCreatesFullDeck(): void
    {
        /* Assert that the deck has 52 cards */
        /* Maybe redundant? */
        $this->assertCount(52, $this->deck->getCards());

        /* Assert that the deck has 52 unique cards */
        $cardsAsString = $this->deck->getCardsAsString();
        $uniqueCardsAsString = array_unique($cardsAsString);
        $this->assertCount(52, $uniqueCardsAsString);
    }
}
