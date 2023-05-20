<?php

namespace App\Proj;

class GameObject
{
    private string $image;
    private int $positionX;
    private int $positionY;
    // @phpstan-ignore-next-line
    private ?int $positionZ = 0;
    private bool $clickable;
    private string $name;
    /**
     * @var array<string, mixed>
     */
    private ?array $options = null;
    private int $objId;
    private ?string $effect;
    private ?int $width = null;
    private ?int $height = null;

    // @phpstan-ignore-next-line
    public function __construct(
        int $objId,
        string $image,
        string $name,
        int $positionX = 0,
        int $positionY = 0,
        int $positionZ = 0,
        bool $clickable = false,
        ?array $options = [],
        ?string $effect = null,
        ?int $width = null,
        ?int $height = null
    ) {
        $this->objId = $objId;
        $this->image = $image;
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->positionZ = $positionZ;
        $this->clickable = $clickable;
        $this->name = $name;
        $this->options = $options ?? [];
        $this->effect = $effect;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Retrieves the object id.
     *
     * @return int The object id.
     */
    public function getObjId(): int 
    {
        return $this->objId;
    }

    public function setObjId(int $objId): void 
    {
        $this->objId = $objId;
    }

    /**
     * Retrieves the effect.
     *
     * @return string The effect as string.
     */
    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPositionX(): int
    {
        return $this->positionX;
    }

    public function getPositionY(): int
    {
        return $this->positionY;
    }

    public function getPositionZ(): int
    {
        return $this->positionY;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function isClickable(): bool
    {
        return $this->clickable;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function onClick(): array
    {
        $options = [];
        foreach ($this->options as $eventId => $eventName) {
            $options[] = [
                'eventId' => $eventId,
                'eventName' => $eventName,
            ];
        }
        return $options;
    }
    
    /**
     * @return array<mixed, mixed>
     */
    public function getOptions(): array
    {
        $options = [];
        foreach ($this->options as $optionKey => $eventId) {
            $options[$optionKey] = $eventId;
        }
        return $options;
    }
    
    
    public function getEventByOption(mixed $option): mixed
    {
        return isset($this->options[$option]) ? $this->options[$option] : null;
    }
    
    public function addOption(int|string $key, mixed $value): void
    {
        /** @phpstan-ignore-next-line */
        $this->options[$key] = $value;
        error_log("Added option: key = $key, value = $value", 0);
    }
    
    
}
