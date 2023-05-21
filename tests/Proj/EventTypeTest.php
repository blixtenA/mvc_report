<?php

namespace Tests\App\Proj;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\Form\Test\TypeTestCase;

class EventTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'text' => 'Event text',
            'name' => 'Event name',
        ];

        $event = new Event();

        $form = $this->factory->create(EventType::class, $event);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($event, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
