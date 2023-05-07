<?php

namespace Tests\App\Card;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

class CardHandTest extends TestCase
{
    private CardHand $hand1;
    private CardHand $hand2;
    private CardHand $hand3;
    private DeckOfCards $deck;

    protected function setUp(): void
    {
        $this->deck = new DeckOfCards();
        $this->hand1 = new CardHand($this->deck, 2);
        $this->hand2 = new CardHand($this->deck, 3);
        $this->hand3 = new CardHand($this->deck, 4);
    }

    public function testCardHandConstructor(): void
    {
        $this->assertInstanceOf(CardHand::class, $this->hand1);
    }

    public function testGetCards(): void
    {
        $this->assertCount(2, $this->hand1->getCards());
        $this->assertCount(3, $this->hand2->getCards());
        $this->assertCount(4, $this->hand3->getCards());
    }

    public function testAddCard(): void
    {
        $card = new Card('Ace', 'Spades');
        $this->hand1->addCard($card);
        $this->assertCount(3, $this->hand1->getCards());
        $this->assertSame($card, $this->hand1->getCards()[2]);
    }

    public function testGetCardsAsString(): void
    {
        $this->assertIsArray($this->hand1->getCardsAsString());
        $this->assertContainsOnly('string', $this->hand1->getCardsAsString());
    }

    public function testHasAce(): void
    {
        $this->deck->mixCards();
    
        $handWithAce = new CardHand($this->deck);
        $handWithAce->addCard(new Card('Ace', 'Hearts'));
    
        $handWithoutAce = new CardHand($this->deck);
    
        do {
            $handWithoutAce = new CardHand($this->deck);
        } while ($handWithoutAce->hasAce());
    
        $this->assertTrue($handWithAce->hasAce());
        $this->assertFalse($handWithoutAce->hasAce());
    }

    public function testGetScore(): void
    {
        $this->deck->mixCards();
        $hand = new CardHand($this->deck, 1);
        $card1 = $hand->getCards()[0];
        $value1 = $card1->getValue();
    
        $card2 = new Card('2', 'Diamonds');
        $hand->addCard($card2);
        $value2 = $card2->getValue();
    
        $map = [
            'Ace' => 1,
            'King' => 13,
            'Queen' => 12,
            'Jack' => 11,
        ];
        $expected = (isset($map[$value1]) ? $map[$value1] : intval($value1)) + (isset($map[$value2]) ? $map[$value2] : intval($value2));    
        $this->assertContains($hand->getScore(), [$expected, $expected + 13]);
    }
    

    public function testIsGameOver(): void
    {
        $this->deck->mixCards();
        $hand = new CardHand($this->deck, 2);
        while ($hand->getScore() < 18 || $hand->getScore() > 20) {
            $hand = new CardHand($this->deck, 2);
        }

        $this->assertFalse($hand->isGameOver('player'));
        $this->assertTrue($hand->isGameOver('bank'));
    }

    public function testIsGameOverMoreThan21(): void
    {
        $this->deck->mixCards();
        $hand = new CardHand($this->deck, 22);

        $this->assertTrue($hand->isGameOver('player'));
        $this->assertTrue($hand->isGameOver('bank'));
    }


}
