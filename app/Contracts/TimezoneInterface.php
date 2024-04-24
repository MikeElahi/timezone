<?php

namespace App\Contracts;

interface TimezoneInterface
{
    public function getName(): string;

    public function getOffset(): string;

    public function isDst(): bool;


    public function setName(string $name): void;

    public function setOffset(string $offset): void;

    public function setIsDst(bool $isDst): void;

}
