<?php

namespace Tests\App\Proj;

use App\Entity\Room;
use App\Form\RoomType;
use Symfony\Component\Form\Test\TypeTestCase;

class RoomTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'background' => 'background value',
            'name' => 'name value',
            'description' => 'description value',
        ];

        $room = new Room();

        $form = $this->factory->create(RoomType::class, $room);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($room, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
