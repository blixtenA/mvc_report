<?php

namespace App\Card;

use App\Card\Card;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class CardTest extends TestCase
{
    private Card $aceOfSpades;

    protected function setUp(): void
    {
        $this->aceOfSpades = new Card('Ace', 'Spades');
    }

    public function testGetValue(): void
    {
        $this->assertEquals('Ace', $this->aceOfSpades->getValue());
    }

    public function testGetFamily(): void
    {
        $this->assertEquals('Spades', $this->aceOfSpades->getFamily());
    }

    public function testGetAsString(): void
    {
        $this->assertEquals('Ace of Spades', $this->aceOfSpades->getAsString());
    }

    public function testGetSymbol(): void
    {
        $this->assertEquals('🂡', $this->aceOfSpades->getSymbol());
    }

    public function testCreateEmptyCard(): void
    {
        $card = new Card();
        $this->assertInstanceOf(Card::class, $card);
    }
}
