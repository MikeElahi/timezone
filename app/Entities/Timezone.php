<?php

namespace App\Entities;

use App\Contracts\TimezoneInterface;

final class Timezone implements TimezoneInterface
{
    private string $name;

    private string $offset;

    private bool $isDst;

    public function __construct(string $name, string $offset, bool $isDst)
    {
        $this->name = $name;
        $this->offset = $offset;
        $this->isDst = $isDst;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getOffset(): string
    {
        return $this->offset;
    }

    public function isDst(): bool
    {
        return $this->isDst;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setOffset(string $offset): void
    {
        $this->offset = $offset;
    }

    public function setIsDst(bool $isDst): void
    {
        $this->isDst = $isDst;
    }
}
