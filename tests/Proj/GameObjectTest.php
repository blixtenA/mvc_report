<?php

namespace Tests\App\Proj;

use App\Proj\GameObject;
use PHPUnit\Framework\TestCase;

class GameObjectTest extends TestCase
{
    private GameObject $gameObject;

    public function setUp(): void
    {
        $objId = 1;
        $image = 'image.jpg';
        $name = 'Example Object';
        $positionX = 10;
        $positionY = 20;
        $positionZ = 0;
        $options = ['option1', 'option2'];
        $effect = 'example effect';
        $width = 100;
        $height = 200;
        $image2 = 'image2.jpg';

        $gameObject = new GameObject(
            $objId,
            $image,
            $name,
            $positionX,
            $positionY,
            $positionZ,
            $options,
            $effect,
            $width,
            $height,
            $image2
        );

        $this->gameObject = $gameObject;

        $this->assertInstanceOf(GameObject::class, $gameObject);
        $this->assertSame($objId, $gameObject->getObjId());
        $this->assertSame($image, $gameObject->getImage());
        $this->assertSame($name, $gameObject->getName());
    
    }

    public function testGetEffect(): void
    {
        $effect = 'example effect';
        $gameObject = new GameObject();
        $gameObject->setEffect($effect);
        $this->assertSame($effect, $gameObject->getEffect());
    }

    public function testSetEffect(): void
    {
        $effect = 'example effect';
        $gameObject = new GameObject();
        $gameObject->setEffect($effect);
        $this->assertSame($effect, $gameObject->getEffect());
    }

    public function testGetImage(): void
    {
        $image = 'example.jpg';
        $gameObject = new GameObject();
        $gameObject->setImage($image);
        $this->assertSame($image, $gameObject->getImage());
    }

    public function testSetImage(): void
    {
        $image = 'example.jpg';
        $gameObject = new GameObject();
        $gameObject->setImage($image);
        $this->assertSame($image, $gameObject->getImage());
    }

    public function testGetImage2(): void
    {
        $image2 = 'example2.jpg';
        $gameObject = new GameObject();
        $gameObject->setImage2($image2);
        $this->assertSame($image2, $gameObject->getImage2());
    }

    public function testSetImage2(): void
    {
        $image2 = 'example2.jpg';
        $gameObject = new GameObject();
        $gameObject->setImage2($image2);
        $this->assertSame($image2, $gameObject->getImage2());
    }

    public function testGetName(): void
    {
        $name = 'Example Object';
        $gameObject = new GameObject();
        $gameObject->setName($name);
        $this->assertSame($name, $gameObject->getName());
    }

    public function testSetName(): void
    {
        $name = 'Example Object';
        $gameObject = new GameObject();
        $gameObject->setName($name);
        $this->assertSame($name, $gameObject->getName());
    }

    public function testSetPositionX(): void
    {
        $positionX = 10;
        $gameObject = new GameObject();
        $gameObject->setPositionX($positionX);
        $this->assertSame($positionX, $gameObject->getPositionX());
    }

    public function testGetPositionX(): void
    {
        $positionX = 10;
        $gameObject = new GameObject();
        $gameObject->setPositionX($positionX);
        $this->assertSame($positionX, $gameObject->getPositionX());
    }

    public function testSetPositionY(): void
    {
        $positionY = 20;
        $gameObject = new GameObject();
        $gameObject->setPositionY($positionY);
        $this->assertSame($positionY, $gameObject->getPositionY());
    }

    public function testGetPositionY(): void
    {
        $positionY = 20;
        $gameObject = new GameObject();
        $gameObject->setPositionY($positionY);
        $this->assertSame($positionY, $gameObject->getPositionY());
    }

    public function testSetPositionZ(): void
    {
        $positionZ = 30;
        $this->gameObject->setPositionZ($positionZ);
        $this->assertSame($positionZ, $this->gameObject->getPositionZ());
    }

    public function testGetPositionZ(): void
    {
        $positionZ = 30;
        $this->gameObject->setPositionZ($positionZ);
        $this->assertSame($positionZ, $this->gameObject->getPositionZ());
    }

    public function testGetHeight(): void
    {
        $height = 100;
        $gameObject = new GameObject();
        $gameObject->setHeight($height);
        $this->assertSame($height, $gameObject->getHeight());
    }

    public function testSetHeight(): void
    {
        $height = 100;
        $gameObject = new GameObject();
        $gameObject->setHeight($height);
        $this->assertSame($height, $gameObject->getHeight());
    }

    public function testGetWidth(): void
    {
        $width = 200;
        $gameObject = new GameObject();
        $gameObject->setWidth($width);
        $this->assertSame($width, $gameObject->getWidth());
    }

    public function testSetWidth(): void
    {
        $width = 200;
        $gameObject = new GameObject();
        $gameObject->setWidth($width);
        $this->assertSame($width, $gameObject->getWidth());
    }

    public function testHasClickOptions(): void
    {
        $gameObject = new GameObject();
        $this->assertFalse($gameObject->hasClickOptions());
    
        $gameObject->addOption(1, 'Option 1');
        $this->assertTrue($gameObject->hasClickOptions());
    }

    public function testOnClick(): void
    {
        $gameObject = new GameObject();
        $this->assertSame([], $gameObject->onClick());

        $gameObject->addOption(1, 'Option 1');
        $gameObject->addOption(2, 'Option 2');
        $expectedResult = [
            [
                'eventId' => 1,
                'eventName' => 'Option 1',
            ],
            [
                'eventId' => 2,
                'eventName' => 'Option 2',
            ],
        ];
        $this->assertSame($expectedResult, $gameObject->onClick());
    }

    public function testGetOptions(): void
    {
        $options = [
            'option1' => 1,
            'option2' => 2,
            'option3' => 3,
        ];

        $gameObject = new GameObject();
        $reflection = new \ReflectionClass($gameObject);
        $optionsProperty = $reflection->getProperty('options');
        $optionsProperty->setAccessible(true);
        $optionsProperty->setValue($gameObject, $options);

        $expectedResult = $options;
        $actualResult = $gameObject->getOptions();

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testGetEventByOptionWithExistingOption(): void
    {
        $options = [
            'option1' => 'event1',
            'option2' => 'event2',
            'option3' => 'event3',
        ];

        $gameObject = new GameObject();
        $reflection = new \ReflectionClass($gameObject);
        $optionsProperty = $reflection->getProperty('options');
        $optionsProperty->setAccessible(true);
        $optionsProperty->setValue($gameObject, $options);

        $option = 'option2';

        $expectedResult = 'event2';
        $actualResult = $gameObject->getEventByOption($option);

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testGetEventByOptionWithNonExistingOption(): void
    {
        $options = [
            'option1' => 'event1',
            'option2' => 'event2',
            'option3' => 'event3',
        ];

        $gameObject = new GameObject();
        $reflection = new \ReflectionClass($gameObject);
        $optionsProperty = $reflection->getProperty('options');
        $optionsProperty->setAccessible(true);
        $optionsProperty->setValue($gameObject, $options);

        $option = 'option4';

        $expectedResult = null;
        $actualResult = $gameObject->getEventByOption($option);

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testAddOption(): void
    {
        $gameObject = new GameObject();

        $key = 'option1';
        $value = 'event1';

        $gameObject->addOption($key, $value);

        $reflection = new \ReflectionClass($gameObject);
        $optionsProperty = $reflection->getProperty('options');
        $optionsProperty->setAccessible(true);
        $options = $optionsProperty->getValue($gameObject);

        $expectedOptions = [
            'option1' => 'event1',
        ];

        $this->assertSame($expectedOptions, $options);
    }


}