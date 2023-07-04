<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Method implements OptionSourceInterface
{
    const GET = 'GET';
    const HEAD = 'HEAD';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const CONNECT = 'CONNECT';
    const OPTIONS = 'OPTIONS';
    const TRACE = 'TRACE';
    const PATCH = 'PATCH';

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
