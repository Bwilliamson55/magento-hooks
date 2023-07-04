<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Bwilliamson\Hooks\Model\Config\AbstractSource;

class Status extends AbstractSource
{
    public const SUCCESS = 1;
    public const ERROR = 0;

    /**
     * Returns an array of status options.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::SUCCESS => __('Success'),
            self::ERROR => __('Error'),
        ];
    }
}
