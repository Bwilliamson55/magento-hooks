<?php

namespace Bwilliamson\Hooks\Model;

interface HookFactoryInterface
{
    /**
     * Create a new instance of the Hook model
     *
     * @param array $data
     * @return Hook
     */
    public function create(array $data = []): Hook;
}
