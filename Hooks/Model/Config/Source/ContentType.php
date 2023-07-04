<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Bwilliamson\Hooks\Model\Config\AbstractSource;

class ContentType extends AbstractSource
{
    public const APPLICATION_JSON = 'application/json';
    public const APPLICATION_X_WWW_FORM_URLENCODE = 'application/x-www-form-urlencoded';
    public const APPLICATION_XML = 'application/xml';
    public const APPLICATION_JSON_CHARSET_UTF_8 = 'application/json; charset=UTF-8';

    /**
     * Returns an array of content types.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            '' => __('--Please Select--'),
            self::APPLICATION_JSON => 'application/json',
            self::APPLICATION_X_WWW_FORM_URLENCODE => 'application/x-www-form-urlencoded',
            self::APPLICATION_XML => 'application/xml',
            self::APPLICATION_JSON_CHARSET_UTF_8 => 'application/json; charset=UTF-8',
        ];
    }
}
