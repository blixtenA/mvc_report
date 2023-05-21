<?php

namespace Tests\App\Proj;

use App\Entity\ObjectByRoom;
use App\Form\ObjectByRoomType;
use Symfony\Component\Form\Test\TypeTestCase;

class ObjectByRoomTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'room_id' => 1,
            'object_id' => 2,
            'sequence' => 3,
            'position_x' => 10,
            'position_y' => 20,
            'position_z' => 30,
            'width' => 100,
            'height' => 200,
        ];

        $objectByRoom = new ObjectByRoom();

        $form = $this->factory->create(ObjectByRoomType::class, $objectByRoom);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($objectByRoom, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    
}