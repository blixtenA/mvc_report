<?php

namespace Tests\App\Proj;

use App\Proj\Action;
use App\Proj\Game;
use App\Proj\Event;
use App\Proj\GameObject;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    private Action $action;
    private Game $game;
    private Event $event;
    private ?GameObject $object;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->game = $this->createMock(Game::class);
        $this->event = $this->createMock(Event::class);
        $this->object = $this->createMock(GameObject::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        
        $this->action = new Action($this->game, $this->event, $this->object, $this->entityManager);
    }


}