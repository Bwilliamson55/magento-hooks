<?php

namespace Bwilliamson\Hooks\Service;

use Bwilliamson\Hooks\Api\HooksServiceInterface;

class HooksService implements HooksServiceInterface
{
    /**
     * @param HooksSession $hooksSession
     */
    public function __construct(
        private readonly HooksSession $hooksSession
    ) {
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getValue(string $key): mixed
    {
        return $this->hooksSession->getData($key);
    }

    /**
     * @param string $key
     * @param $value
     * @return void
     */
    public function setValue(string $key, $value): void
    {
        $this->hooksSession->setData($key, $value);
    }
}
