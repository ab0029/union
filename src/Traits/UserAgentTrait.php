<?php

namespace Young\Union\Traits;

use Adbar\Dot;

/**
 * Trait HasDataTrait
 *
 * @package   Young\Union\Traits
 */
trait UserAgentTrait
{
    public static $isRandomUserAgent = false;

    public static $defaultUserAgent = 'Mozilla/5.0';

    public static $randomUserAgents = [];

    public static function userAgent() {
        if ( static::$isRandomUserAgent && count(static::$randomUserAgents) > 0) {
            return array_rand(static::$randomUserAgents);
        }
        return static::$defaultUserAgent;
    }
}