<?php

namespace Tests\App\Proj;

use App\Entity\GameObject;
use App\Form\GameObjectType;
use Symfony\Component\Form\Test\TypeTestCase;

class GameObjectTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'id' => 1,
            'image' => 'image.png',
            'name' => 'Object',
            'image2' => 'image2.png',
            'effect' => 'Effect',
        ];

        $gameObject = new GameObject();

        $form = $this->factory->create(GameObjectType::class, $gameObject);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($gameObject, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

}
