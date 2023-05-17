<?php

namespace App\Proj;

class Action
{
    private $game;
    private $event;
    private $object;
    private $messages;

    public function __construct($game, $event, $object, $messages = null)
    {
        $this->game = $game;
        $this->event = $event;
        $this->object = $object;
        $this->messages = [];
    }

    public function perform()
    {
        error_log("perform", 0);
        $eventActions = $this->event->getActions();

        foreach ($eventActions as $actionType) {
            if ($this->game->getGameState() === 'gameover') {
                return false;
            }

            error_log("action: ". $actionType, 0);
            /* Perform different actions based on the $actionType */
            switch ($actionType) {
                case 'addToInventory':
                    $this->addToInventory();
                    break;
                case 'deathEvent':
                    $this->deathEvent();
                    break;
                // Add more cases for other action types
                default:
                    break;
            }
        }
        return $this->messages;
    }

function getGame() 
{
    return $this->game;
}

/* Action: move an object from the current room to the player's inventory */
function addToInventory() 
{
        /* Get the current room */
        $currentRoom = $this->game->getCurrentRoom();

        /* Find the object to remove from the current room */
        $removedObject = $this->object;
    
        /* Remove the object from the current room */
        $currentRoom->removeGameObject($removedObject);

        /* Get the player from the game */
        $player = $this->game->getPlayer();
    
        /* Create the new object for the player's inventory */
        $newObject = new GameObject(
            $removedObject->getObjId(),
            $removedObject->getImage(),
            $removedObject->getPositionX(),
            $removedObject->getPositionY(),
            $removedObject->getName(),
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
    /* Get the current room */
    $currentRoom = $this->game->getCurrentRoom();
    
    $currentRoom->removeAllGameObjects();
    $currentRoom->setBackground('img/proj/backgrounds/deathScreen.png');

    $this->game->setGameState = 'Game Over';
    $this->messages [] = $this->event->getText();
    $this->messages [] = "Player was killed by ". $this->object->getName() . " in " . $currentRoom->getName();
}

}