<?php

namespace Tests\App\Proj;

use App\Entity\Action;
use App\Form\ActionType;
use Symfony\Component\Form\Test\TypeTestCase;

class ActionTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'event_action' => 'Some action',
            'text' => 'Some text',
            'option_yes' => 1,
            'option_no' => 0,
            'option_object' => 2,
        ];

        $action = new Action();

        $form = $this->factory->create(ActionType::class, $action);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($action, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
