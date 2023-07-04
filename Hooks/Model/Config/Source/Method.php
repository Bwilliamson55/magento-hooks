<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Method implements OptionSourceInterface
{
    public const GET = 'GET';
    public const HEAD = 'HEAD';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
    public const CONNECT = 'CONNECT';
    public const OPTIONS = 'OPTIONS';
    public const TRACE = 'TRACE';
    public const PATCH = 'PATCH';

    /**
     * Returns an array of methods for the dropdown/select field.
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
     * Returns an array of methods.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            '' => __('--Please Select--'),
            self::GET => 'GET',
            self::HEAD => 'HEAD',
            self::POST => 'POST',
            self::PUT => 'PUT',
            self::DELETE => 'DELETE',
            self::CONNECT => 'CONNECT',
            self::OPTIONS => 'OPTIONS',
            self::TRACE => 'TRACE',
            self::PATCH => 'PATCH',
        ];
    }
}
