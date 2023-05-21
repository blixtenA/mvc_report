<?php

namespace Tests\App\Proj;

use App\Entity\EventByObject;
use App\Form\EventByObjectType;
use Symfony\Component\Form\Test\TypeTestCase;

class EventByObjectFormTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'objectId' => 1,
            'eventId' => 2,
            'location' => 3,
            'action1' => 4,
            'action2' => 5,
            'action3' => 6,
            'action4' => 7,
            'action5' => 8,
        ];

        $eventByObject = new EventByObject();

        $form = $this->factory->create(EventByObjectType::class, $eventByObject);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($eventByObject, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
