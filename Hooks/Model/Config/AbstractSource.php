<?php

namespace Bwilliamson\Hooks\Model\Config;

use Magento\Framework\Data\OptionSourceInterface;

abstract class AbstractSource implements OptionSourceInterface
{
    /**
     * Returns an array of options for the dropdown/select field.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];

        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * Returns an array of options.
     *
     * @return array
     */
    abstract public function toArray(): array;
}
