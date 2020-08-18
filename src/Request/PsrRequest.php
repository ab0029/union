<?php

namespace Young\Union\Request;

use Countable;
use ArrayAccess;
use IteratorAggregate;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Young\Union\Traits\HasDataTrait;

class PsrRequest extends Request implements ArrayAccess, IteratorAggregate, Countable
{
    use HasDataTrait;

    public function __construct(RequestInterface $request, array $options = [])
    {
        parent::__construct(
            $request->getMethod(),
            $request->getUri(),
            $request->getHeaders(),
            $request->getBody(),
            $request->getProtocolVersion()
        );

        $this->dot($options);
    }
}