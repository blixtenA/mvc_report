<?php

namespace App\Proj;

use Doctrine\ORM\EntityManagerInterface;


class Action
{
    private Game $game;
    private Event $event;
    private ?GameObject $object;
    private EntityManagerInterface $entityManager;
        /**
     * @var array<string>
     */
    private array $messages = [];
    private Room $room;
    private array $eventActions = [];

    public function __construct(Game $game, Event $event, ?GameObject $object, EntityManagerInterface $entityManager)
    {
        $this->game = $game;
        $this->event = $event;
        $this->object = $object;
        $this->entityManager = $entityManager;
        $this->messages = [];
        $this->room = $this->game->getCurrentRoom();
    }

    /**
     * Perform the operation.
     *
     * @return string[]|null Array of strings or null.
     */
    public function perform(): ?array
    {
        $this->eventActions = $this->event->getActions();
        $key = 0;
        $totalActions = count($this->eventActions);
    
        while ($key < $totalActions) {
            $actionId = $this->eventActions[$key];
    
            if ($this->game->getGameState() === 'Game Over') {
                $this->addFinalComments();
                return $this->messages;
            }
    
            if ($actionId !== null) {
                $action = $this->fetchAction($actionId);
                $eventAction = $action->getEventAction();
    
                $this->executeAction($eventAction);
                $this->messages[] = $action->getText();
    
                /* Determine if $this->eventActions has been updated */
                $updatedEventActions = $this->event->getActions();
    
                if (count($updatedEventActions) > $totalActions) {
                    /* Additional actions have been added, adjust the loop */
                    $this->eventActions = $updatedEventActions;
                    $totalActions = count($this->eventActions);
                    $key++;
                }
            }    
            $key++;
        }
    
        return $this->messages;
    }
    
    private function executeAction(string $eventAction): void
    {
        if ($eventAction === 'removeFromRoom') {
            $this->removeFromRoom();
        } elseif ($eventAction === 'addToInventory') {
            $this->addToInventory();
        } elseif ($eventAction === 'deathBySharpObject') {
            $this->deathEvent();
        } elseif ($eventAction === 'deathByHeavyObject') {
            $this->deathEvent();
        } elseif ($eventAction === 'unlockTry') {
            $this->unlockTry();
        } elseif ($eventAction === 'unlockYes') {
            $this->unlockYes();
        } elseif ($eventAction === 'unlockNo') {
            $this->unlockNo();                        
        } elseif (strpos($eventAction, 'walk') === 0) {
            $direction = substr($eventAction, 4);
            $this->walk($direction);
        }        
    }
    
    /**
     * Fetches an Action entity by its ID.
     *
     * @param int $actionId The ID of the action.
     * @return \App\Entity\Action|null The fetched Action entity, or null if not found.
     */
    private function fetchAction(int $actionId): ?\App\Entity\Action
    {
        $entityActionRepository = $this->entityManager->getRepository(\App\Entity\Action::class);
        $entityAction = $entityActionRepository->find($actionId);
    
        if ($entityAction) {
            error_log("Object found", 0);
        } else {
            error_log("Object not found", 0);
        }
    
        return $entityAction;
    }

    /**
     * @return array<string>
     */
    public function getMessages(): array 
    {
        return $this->messages;
    }

    function getGame(): Game
    {
        return $this->game;
    }

    function unlockTry(): void 
    {
        error_log("unnlock try",0);
        $checkInventory = $this->game->getPlayer()->getInventory();
        $unlock = false;

        foreach ($checkInventory as $item) {
            if ($item instanceof GameObject && $item->getName() === "key") {
                $unlock = true;
                break;
            }
        }
        $emptyIndex = array_search('', $this->eventActions);
        if ($unlock) {
            error_log("adding 16",0);
            if ($emptyIndex !== false) {
                $this->eventActions[$emptyIndex] = 16;
            } else {
                $this->eventActions[] = 16;
            }
        }
        else {
            error_log("adding 17",0);
            if ($emptyIndex !== false) {
                $this->eventActions[$emptyIndex] = 17;
            } else {
                $this->eventActions[] = 17;
            }
        }

    }

    function unlockYes(): void 
    {
        error_log("unnlock yes",0);
        $this->messages [] = $this->event->getText(); /* wrong text */
        $this->removeFromRoom();
        $this->room->sequenceAdvance();
    }

    function unlockNo(): void 
    {
        error_log("unnlock no",0);
        $this->messages [] = $this->event->getText(); /* no */
    }

    /* Remove the object from the current room */
    function removeFromRoom(): void
    {            
        $this->room->removeGameObject($this->object);
    }

    /* Action: move an object from the current room to the player's inventory */
    function addToInventory() : void
    {

        /* Get the player from the game */
        $player = $this->game->getPlayer();
    
        /* Create the new object for the player's inventory */
        $newObject = new GameObject(
            $this->object->getObjId(),
            $this->object->getImage(),
            $this->object->getName(),
            $this->object->getPositionX(),
            $this->object->getPositionY(),
            $this->object->getPositionZ(),
            true,
            NULL,
            $this->object->getEffect(),
        );

        /* Add the object to the player's inventory */
        $player->addToInventory($newObject);
        $this->messages [] = $this->event->getText();
    }

    private function deathEvent(): void
    {    
        $this->room->removeAllGameObjects();
        $this->room->setBackground('img/proj/backgrounds/deathScreen.png');

        $this->game->setGameState('Game Over');
        $this->messages [] = $this->event->getText();
    }

    private function addFinalComments(): void 
    {
        error_log("in",0);
        $this->messages [] = 
            "Player was killed by ".
            $this->object->getName() . " in " .
            $this->room->getName();
    }

    private function walk(string $direction): void
    {

        /* Check if the current room has a neighbor in the specified direction */
        if (isset($this->room->getNeighbors()[$direction])) {

            /* Retrieve the neighboring room */
            $neighborRoom = $this->room->getNeighbors()[$direction];
    
            /* Set the neighboring room as the current room */
            $this->game->setCurrentRoom($neighborRoom);
        }
    }    

}