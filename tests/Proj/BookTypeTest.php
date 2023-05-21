<?php

namespace Tests\App\Proj;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Component\Form\Test\TypeTestCase;

class BookTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'title' => 'The Great Gatsby',
            'ISBN' => '9781234567890',
            'author' => 'F. Scott Fitzgerald',
            'image' => 'gatsby.jpg',
        ];

        $book = new Book();

        $form = $this->factory->create(BookType::class, $book);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($book, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
