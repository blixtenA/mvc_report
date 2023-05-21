<?php

namespace Tests\App\Proj;

use App\Entity\Game;
use App\Form\GameType;
use Symfony\Component\Form\Test\TypeTestCase;

class GameTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'game_id' => 1,
            'room_id' => 2,
            'pos_x' => 10,
            'pos_y' => 20,
        ];

        $game = new Game();

        $form = $this->factory->create(GameType::class, $game);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($game, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
