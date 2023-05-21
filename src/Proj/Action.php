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
    private array $eventActions = [];
    private $reloadRoom = false;
    private $currentAction = null;

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
            error_log("action id: ".$actionId,0);
    
            if ($this->game->getGameState() === 'Game Over') {
                $this->addFinalComments();
                return $this->reloadRoom;
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
     * Execute the given event action.
     *
     * @param string $eventAction The event action to execute.
     * @return void
     */
    private function executeAction(string $eventAction): void
    {
        error_log("Action: ". $eventAction,0);
        if ($eventAction === 'removeFromRoom') {
            $this->removeFromRoom();
        } elseif ($eventAction === 'addToInventory') {
            $this->addToInventory();
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
            $this->addRead();
        } elseif ($eventAction === 'addThrow') {
            $this->addThrow();                     
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
//        $this->object->setImage($this->object->getImage2());
        $this->room->setBackground('img/proj/backgrounds/deathScreen.png');

        $this->game->setGameState('Game Over');
        $this->messages [] = $this->event->getText();
    }

    /**
     * Add the "Throw" option to the object.
     *
     * @return void
     */
    function addThrow(): void 
    {
        $this->object->addOption(20, "Throw");
    }

        /**
     * Add the "Read" option to the object.
     *
     * @return void
     */
    function addRead(): void 
    {
        error_log("add read",0);
        $this->object->addOption(21, "Read");
    }

    /**
     * Attempt to unlock, or other options/object action. 
     * Check if the player has the key
     */
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
        } else {
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
     * Add final comments
     */
    private function addFinalComments(): void 
    {
        $this->messages [] = 
            "Player was killed by ".
            $this->object->getName() . " in " .
            $this->room->getName();
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