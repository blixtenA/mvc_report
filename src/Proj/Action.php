<?php

namespace App\Proj;

use Doctrine\ORM\EntityManagerInterface;


class Action
{
    private $game;
    private $event;
    private $object;
    private $entityManager;
    private $messages;
    private $room;

    public function __construct($game, $event, $object, EntityManagerInterface $entityManager)
    {
        $this->game = $game;
        $this->event = $event;
        $this->object = $object;
        $this->entityManager = $entityManager;
        $this->messages = [];
        $this->room = $this->game->getCurrentRoom();
    }

    public function perform()
    {
        error_log("perform", 0);
        $eventActions = $this->event->getActions();
        error_log("Number of event actions: " . count($eventActions), 0);
    
        foreach ($eventActions as $actionId) {
            if ($this->game->getGameState() === 'gameover') {
                return false;
            }
    
            if ($actionId !== null) {
    
            $action = $this->fetchAction($actionId);

                $eventAction = $action->getEventAction();
    
                error_log("eventAction: " . $eventAction, 0);
    
                $this->executeAction($eventAction);
            }
        }
        return $this->messages;
    }
    
    private function executeAction($eventAction)
    {
        if ($eventAction === 'removeFromRoom') {
            $this->removeFromRoom();
        } elseif ($eventAction === 'addToInventory') {
            $this->addToInventory();
        } elseif ($eventAction === 'deathBySharpObject') {
            $this->deathEvent();
        } elseif (strpos($eventAction, 'walk') === 0) {
            $direction = substr($eventAction, 4); // Extract the direction from the event action
            $this->walk($direction);
        }        
    }
    
    private function fetchAction($actionId)
    {
        error_log("Enter fetchAction", 0);
        $entityActionRepository = $this->entityManager->getRepository(\App\Entity\Action::class);
        $entityAction = $entityActionRepository->find($actionId);
    
        if ($entityAction) {
            error_log("Object found", 0);
        } else {
            error_log("Object not found", 0);
        }
    
        return $entityAction;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    function getGame() 
    {
        return $this->game;
    }

    /* Remove the object from the current room */
    function removeFromRoom() 
    {            
        $this->room->removeGameObject($this->object);
    }

    /* Action: move an object from the current room to the player's inventory */
    function addToInventory() 
    {

        /* Get the player from the game */
        $player = $this->game->getPlayer();
    
        /* Create the new object for the player's inventory */
        $newObject = new GameObject(
            $this->object->getObjId(),
            $this->object->getImage(),
            $this->object->getPositionX(),
            $this->object->getPositionY(),
            $this->object->getPositionZ(),
            $this->object->getName(),
            true,
            [
                "Throw" => 3,
                "Eat" => 4
            ]
        );

        /* Add the object to the player's inventory */
        $player->addToInventory($newObject);
        $this->messages [] = $this->event->getText();
    }

    private function deathEvent()
    {    
        $this->room->removeAllGameObjects();
        $this->room->setBackground('img/proj/backgrounds/deathScreen.png');

        $this->game->setGameState = 'Game Over';
        $this->messages [] = $this->event->getText();
        $this->messages [] = 
            "Player was killed by ".
            $this->object->getName() . " in " .
            $this->room->getName();
    }

    private function walk($direction)
    {
        error_log("walk ". $direction,0);
        /* Check if the current room has a neighbor in the specified direction */
        if (isset($this->room->getNeighbors()[$direction])) {
            error_log("isset",0);
            /* Retrieve the neighboring room */
            $neighborRoom = $this->room->getNeighbors()[$direction];
            error_log("neighbor: ". $neighborRoom->getId(),0);
            error_log("neighbor: ". $neighborRoom->getName(),0);
    
            /* Set the neighboring room as the current room */
            $this->game->setCurrentRoom($neighborRoom);
        }
    }    

}