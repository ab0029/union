<?php

namespace Young\Union;

class Config
{
    use Traits\HasDataTrait;

    public function __construct(array $config = [])
    {
        $this->dot($config);
    }
}