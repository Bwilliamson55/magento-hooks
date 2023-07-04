<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Bwilliamson\Hooks\Model\Config\AbstractSource;

class Status extends AbstractSource
{
    const SUCCESS = 1;
    const ERROR = 0;

    public function toArray(): array
    {
        return [
            self::SUCCESS => 'Success',
            self::ERROR => 'Error',
        ];
    }
}
