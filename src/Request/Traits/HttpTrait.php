<?php

namespace Young\Union\Request\Traits;

trait HttpTrait
{

    /**
     * @var array
     */
    public $options = [];

    /**
     * @param int|float $seconds
     *
     * @return $this
     */
    public function timeout($seconds)
    {
        $this->options['timeout'] = $seconds;

        return $this;
    }

    /**
     * @param int $milliseconds
     *
     * @return $this
     */
    public function timeoutMilliseconds($milliseconds)
    {
        $seconds = $milliseconds / 1000;

        return $this->timeout($seconds);
    }

    /**
     * @param int|float $seconds
     *
     * @return $this
     */
    public function connectTimeout($seconds)
    {
        $this->options['connect_timeout'] = $seconds;

        return $this;
    }

    /**
     * @param int $milliseconds
     *
     * @return $this
     */
    public function connectTimeoutMilliseconds($milliseconds)
    {
        $seconds = $milliseconds / 1000;

        return $this->connectTimeout($seconds);
    }

    /**
     * @param bool $debug
     *
     * @return $this
     */
    public function debug($debug)
    {
        $this->options['debug'] = $debug;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array $cert
     *
     * @return $this
     */
    public function cert($cert)
    {
        $this->options['cert'] = $cert;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array|string $proxy
     *
     * @return $this
     */
    public function proxy($proxy)
    {
        $this->options['proxy'] = $proxy;

        return $this;
    }

    /**
     * @param mixed $verify
     *
     * @return $this
     */
    public function verify($verify)
    {
        $this->options['verify'] = $verify;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function options(array $options)
    {
        if ($options !== []) {
            $this->options = array_merge([$this->options, $options]);
        }

        return $this;
    }
}
