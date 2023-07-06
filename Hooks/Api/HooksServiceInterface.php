<?php

namespace Bwilliamson\Hooks\Api;

interface HooksServiceInterface
{
    public function getValue(string $key): mixed;
    public function setValue(string $key, $value): void;
}
