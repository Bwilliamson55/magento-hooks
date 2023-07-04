<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Bwilliamson\Hooks\Model\Config\AbstractSource;

class Authentication extends AbstractSource
{
    const BASIC = 'basic';

    public function toArray(): array
    {
        return [
            '' => __('--Please Select--'),
            self::BASIC => __('Basic'),
        ];
    }
}
