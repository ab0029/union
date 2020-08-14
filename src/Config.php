<?php

namespace Young\Union;

use Countable;
use ArrayAccess;
use IteratorAggregate;

class Config implements ArrayAccess, IteratorAggregate, Countable
{
    use Traits\HasDataTrait;

    public function __construct(array $config = [])
    {
        $this->dot($config);
    }
}