<?php

namespace Young\Union\Request\Traits;

use Exception;
use Stringy\Stringy;
use Young\Union\Result\Result;
use Young\Union\Exception\ClientException;
use GuzzleHttp\Psr7\Uri;

/**
 * Trait UriTrait
 *
 * @package Young\Union\Request\Traits
 */
trait UriTrait
{
    protected $uri;

    protected $scheme;

    public function uriScheme(string $scheme)
    {
        $this->scheme = $scheme;
        $this->uri    = $this->uri->withScheme($scheme);
        return $this;
    }

    public function uriHost(string $host)
    {
        $this->uri = $this->uri->withHost($host);
        return $this;
    }

    public function uriPort($port)
    {
        $this->uri = $this->uri->withPort($port);
        return $this;
    }

    public function uriPath(string $path)
    {
        $this->uri = $this->uri->withPath($path);
        return $this;
    }

    public function uriQuery($query)
    {
        if ( is_array($query) ) {
            $this->uri = Uri::withQueryValues($this->uri, $query);
        } else {
            $this->uri = $this->uri->withQuery($query);
        }
        return $this;
    }

    public function setUri($uri = '')
    {
        $this->uri = $uri instanceof Uri ? $uri : new Uri($uri);
        $this->scheme = $this->uri->getScheme();
        return $this;
    }
}