<?php

namespace App\Proj;

use App\Entity\EventByObject;
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
    /**
    * @var array<int>
    */
    private array $eventActions = [];
    private bool $reloadRoom = false;
    private ?\App\Entity\Action $currentAction = null;

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
     * Perform the event.
     *
     * @return bool flag to say if the room needs a reload.
     */
    public function perform(): bool
    {
        $this->eventActions = $this->event->getActions();
        $key = 0;
        $totalActions = count($this->eventActions);
    
        while ($key < $totalActions) {
            $actionId = $this->eventActions[$key];
    
            if ($this->game->getGameState() === 'Game Over') {
                $objectName = $this->object ? $this->object->getName() : 'Unknown Object';
                $roomName = $this->room ? $this->room->getName() : 'Unknown Room';
            
                return $this->addCommentAndHandleState("Player was killed by " . $objectName . " in " . $roomName);
            }
    
            if ($this->game->getGameState() === 'Player Wins') {
                return $this->addCommentAndHandleState("Congratulations, you beat the Cube BTH edition!");
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
    
        return $this->reloadRoom;
    }

    /**
     * Add a comment and handle the game state accordingly.
     *
     * @param string $comment The comment to add.
     * @return bool The value of $reloadRoom.
     */
    private function addCommentAndHandleState(string $comment): bool
    {
        $this->addComment($comment);

        if ($this->game->getGameState() === 'Player Wins') {
            $this->game->setGameState('Game Over');
        }

        return $this->reloadRoom;
    }

    /**
     * Add a comment to the messages array.
     *
     * @param string $comment The comment to add.
     * @return void
     */
    private function addComment(string $comment): void
    {
        $this->messages[] = $comment;
    }
    
    /**
     * Execute the given event action.
     *
     * @param string $eventAction The event action to execute.
     * @return void
     */
    private function executeAction(string $eventAction): void
    {
        if ($eventAction === 'removeFromRoom') {
            $this->removeFromRoom();
        } elseif ($eventAction === 'addToInventory') {
            $this->addToInventory();
        } elseif ($eventAction === 'removeFromInventory') {
            $this->removeFromInventory();
        } elseif ($eventAction === 'deathBySharpObject') {
            $this->deathEvent();
        } elseif ($eventAction === 'deathByHeavyObject') {
            $this->deathEvent();
        } elseif ($eventAction === 'deathByAcid') {
            $this->deathEvent();
        } elseif ($eventAction === 'unlockTry') {
            $this->actionWithOptionsAndObject();
        } elseif ($eventAction === 'unlockYes') {
            $this->unlockYes();
        } elseif ($eventAction === 'unlockNo') {
            $this->failMessage();          
        } elseif ($eventAction === 'deathByBunny') {
            $this->deathByBunny();
        } elseif ($eventAction === 'addRead') {
            $this->addEvent(11, "Read");
        } elseif ($eventAction === 'addThrow') {
            $this->addEvent(5, "Throw");  
        } elseif ($eventAction === 'killBunny') {
            $this->killBunny();   
        } elseif ($eventAction === 'playerWins') {
            $this->playerWins();             
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
        $this->currentAction = $entityAction;    

        return $entityAction;
    }

    /**
     * @return array<string>
     */
    public function getMessages(): array 
    {
        return $this->messages;
    }

    /**
     * Get the game instance.
     *
     * @return Game The game instance.
     */
    function getGame(): Game
    {
        return $this->game;
    }

    /**
     * Handle the death by bunny event.
     *
     * @return void
     */
    function deathByBunny(): void 
    {
        $this->room->removeAllGameObjects();
        $this->room->setBackground('img/proj/backgrounds/deathScreen.png');
        $this->game->setGameState('Game Over');
        $this->messages [] = $this->event->getText();
    }

        /**
     * Handle the player wins event
     *
     * @return void
     */
    function playerWins(): void 
    {
        $this->room->removeAllGameObjects();
        $this->room->setBackground('img/proj/backgrounds/playerWin.png');
        $this->room->setDescription('');
        $this->game->setGameState('Player Wins');
        $this->messages [] = $this->event->getText();
    }

        /**
     * Handle the dead bunny event.
     *
     * @return void
     */
    function killBunny(): void 
    {
        $this->room->removeAllGameObjects();
        $this->reloadRoom = true;
        $this->messages [] = $this->event->getText();
    }

    /**
     * Add the specified event option to the object.
     *
     * @param int $eventId The event ID.
     * @param string $eventName The event name.
     * @return void
     */
    function addEvent(int $eventId, string $eventName): void
    {
        $player = $this->game->getPlayer();

        $matchingObject = null;
        foreach ($player->getInventory() as $gameObject) {
            if ($gameObject->getObjId() === $this->object->getObjId()) {
                $matchingObject = $gameObject;
                break;
            }
        }
        
        if ($matchingObject !== null) {    
            $matchingObject->addOption($eventId, $eventName);
        }
    }

    /**
     * Attempt to unlock, or other options/object action. 
     * Check if the player has the key
     */
    /** @scrutinizer ignore-call */ 
    function actionWithOptionsAndObject(): void 
    {
        $optionObject = $this->currentAction->getOptionObject();
        $checkInventory = $this->game->getPlayer()->getInventory();
        $unlock = false;

        foreach ($checkInventory as $item) {
            if ($item instanceof GameObject && $item->getObjId() === $optionObject) {
                $unlock = true;
                break;
            }
        }
        $emptyIndex = array_search('', $this->eventActions);
        if ($unlock) {
            $newAction = $this->currentAction->getOptionYes();
            $this->reloadRoom = true;
        }
        else {
            $newAction = $this->currentAction->getOptionNo();
        }

        if ($emptyIndex !== false) {
            $this->eventActions[$emptyIndex] = $newAction;
        } 
        else {
            $this->eventActions[] = $newAction;
        }
    }

    /**
     * Unlock an object and update the room
     */
    function unlockYes(): void 
    {
        $this->messages [] = $this->event->getText();
        $this->removeFromRoom();
    }

    /**
     * A simple fail message
     */
    function failMessage(): void 
    {
        $this->messages [] = $this->event->getText(); 
    }

    /* Remove the object from the current room */
    function removeFromRoom(): void
    {            
        $this->room->removeGameObject($this->object);
    }

    /* Remove the object from the inventory */
    function removeFromInventory(): void
    {            
        $this->game->getPlayer()->removeFromInventory($this->object);
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
            NULL,
            $this->object->getEffect(),
        );

        /* Add the object to the player's inventory */
        $player->addToInventory($newObject);
        $this->messages [] = $this->event->getText();
    }

    /**
     * A general death event
     */
    private function deathEvent(): void
    {    
        $this->room->removeAllGameObjects();
        $this->room->setBackground('img/proj/backgrounds/deathScreen.png');

        $this->game->setGameState('Game Over');
        $this->messages [] = $this->event->getText();
    }

    /**
     * Walk from one room to another
     */
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