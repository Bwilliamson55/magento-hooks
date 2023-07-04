<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Bwilliamson\Hooks\Model\Config\AbstractSource;

class Authentication extends AbstractSource
{
    public const BASIC = 'basic';

    /**
     * Returns an array of authentication options.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            '' => __('--Please Select--'),
            self::BASIC => __('Basic'),
        ];
    }
}
