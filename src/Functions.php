<?php

namespace Young\Union;

use Closure;

/**
 * Gets the value of an environment variable.
 *
 * @param string $key
 * @param mixed  $default
 *
 * @return mixed
 */
function env($key, $default = null)
{
    $value = getenv($key);

    if ($value === false) {
        return value($default);
    }

    if (envSubstr($value)) {
        return substr($value, 1, -1);
    }

    return envConversion($value);
}

/**
 * @param $value
 *
 * @return bool|string|null
 */
function envConversion($value)
{
    $key = strtolower($value);

    if ($key === 'null' || $key === '(null)') {
        return null;
    }

    $list = [
        'true'    => true,
        '(true)'  => true,
        'false'   => false,
        '(false)' => false,
        'empty'   => '',
        '(empty)' => '',
    ];

    return isset($list[$key]) ? $list[$key] : $value;
}

/**
 * @param $value
 *
 * @return bool
 */
function envSubstr($value)
{
    return ($valueLength = strlen($value)) > 1 && strpos($value, '"') === 0 && $value[$valueLength - 1] === '"';
}

/**
 * Return the default value of the given value.
 *
 * @param mixed $value
 *
 * @return mixed
 */
function value($value)
{
    return $value instanceof Closure ? $value() : $value;
}

/**
 * @param string $salt
 *
 * @return string
 */
function uuid($salt)
{
    return md5($salt . uniqid(md5(microtime(true)), true)) . time();
}