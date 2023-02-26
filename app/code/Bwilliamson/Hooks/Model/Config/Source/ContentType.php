<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Bwilliamson\Hooks\Model\Config\AbstractSource;

class ContentType extends AbstractSource
{
    const APPLICATION_JSON = 'application/json';
    const APPLICATION_X_WWW_FORM_URLENCODE = 'application/x-www-form-urlencoded';
    const APPLICATION_XML = 'application/xml';
    const APPLICATION_JSON_CHARSET_UTF_8 = 'application/json; charset=UTF-8';

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
