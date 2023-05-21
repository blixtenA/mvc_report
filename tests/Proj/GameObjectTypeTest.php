<?php

namespace Tests\App\Proj;

use App\Entity\GameObject;
use App\Form\GameObjectType;
use Symfony\Component\Form\Test\TypeTestCase;

class GameObjectTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'id' => 1,
            'image' => 'image.png',
            'name' => 'Object',
            'clickable' => true,
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

    public function testReverseTransform()
    {
        $gameObjectType = new GameObjectType();
        $result = $gameObjectType->reverseTransform('value');
        $this->assertNull($result);
    }

    public function testTransform()
    {
        $gameObjectType = new GameObjectType();
        $result = $gameObjectType->transform('value');
        $this->assertNull($result);
    }
}
