<?php

namespace Young\Union\Request;

use Closure;
use ArrayAccess;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Young\Union\Application;
use Young\Union\Encode;
use Young\Union\Result\Result;
use Young\Union\Exceptions\ServerException;
use Young\Union\Exceptions\ClientException;
use Young\Union\Traits\ArrayAccessTrait;
use Young\Union\Traits\ObjectAccessTrait;

abstract class Request implements ArrayAccess
{
    use Traits\UriTrait;
    use Traits\HttpTrait;
    use Traits\RetryTrait;
    use ArrayAccessTrait;
    use ObjectAccessTrait;

    /**
     * Request Connect Timeout
     */
    const CONNECT_TIMEOUT = 5;

    /**
     * Request Timeout
     */
    const TIMEOUT = 10;

    public $format = 'json';

    public $method = 'GET';

    public $data = [];

    private $http_client;

    private static $client_config = [];

    private $responseResolver;

    public function __construct($options = [])
    {
        $this->setUri();
        $this->options['http_errors']     = false;
        $this->options['connect_timeout'] = self::CONNECT_TIMEOUT;
        $this->options['timeout']         = self::TIMEOUT;

        // Turn on debug mode based on environment variable.
        if (\Young\Union\env('UNION_DEBUG') === true) {
            $this->options['debug'] = true;
        }

        // Rewrite configuration if the user has a configuration.
        if ($options !== []) {
            $this->options = array_merge($this->options, $options);
        }
    }

    public function getResult(ResponseInterface $response)
    {
        return call_user_func($this->getResponseResolver(), $response, $this);
    }

    /**
     * Get the user resolver callback.
     *
     * @return \Closure
     */
    public function getResponseResolver()
    {
        return $this->responseResolver ?: function (ResponseInterface $response, Request $request) {
            return new Result($response, $request);
        };
    }

    /**
     * Set the Response resolver callback.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function setResponseResolver(Closure $callback)
    {
        $this->responseResolver = $callback;

        return $this;
    }

    /**
     * @param array $config
     */
    public static function client_config(array $config)
    {
        self::$client_config = $config;
    }

    abstract protected function resolveHost();
    abstract protected function resolveParameter();

    /**
     * @throws ClientException
     * @throws ServerException
     */
    public function resolveOption()
    {
        $this->options['headers']['User-Agent'] = Application::userAgent();

        $this->cleanQuery();
        $this->cleanFormParams();
        $this->resolveHost();
        $this->resolveParameter();

        if (isset($this->options['form_params'])) {
            $this->options['form_params'] = \GuzzleHttp\Psr7\parse_query(
                Encode::create($this->options['form_params'])->toString()
            );
        }
    }

    /**
     * @return Result
     * @throws ClientException
     * @throws ServerException
     */
    public function request()
    {
        $this->resolveOption();
        $result = $this->response();

        if ($this->shouldServerRetry($result)) {
            return $this->request();
        }

        if (!$result->isSuccess()) {
            throw new ServerException($result);
        }

        return $result;
    }

    /***
     * @return PromiseInterface
     * @throws Exception
     */
    public function requestAsync()
    {
        $this->resolveOption();

        return $this->getHttpClient()->requestAsync(
            $this->method,
            (string)$this->uri,
            $this->options
        );
    }

    /**
     * 获取HTTP客户端
     * 不要一直调用self::createClient去走请求，耗时多
     */
    public function getHttpClient()
    {
        return $this->http_client 
                    ? $this->http_client
                    : $this->http_client = self::createClient($this);
    }

    /**
     * @param Request $request
     *
     * @return Client
     * @throws Exception
     */
    public static function createClient(Request $request = null)
    {
        if (Application::hasMock()) {
            $stack = HandlerStack::create(Application::getMock());
        } else {
            $stack = HandlerStack::create();
        }

        if (Application::isRememberHistory()) {
            $stack->push(Middleware::history(Application::referenceHistory()));
        }

        if (Application::getLogger()) {
            $stack->push(Middleware::log(
                Application::getLogger(),
                new MessageFormatter(Application::getLogFormat())
            ));
        }

        $stack->push(Middleware::mapRequest(static function (RequestInterface $psrRequest) use ($request) {
            return new PsrRequest($psrRequest, $request->options);
        }));

        $stack->push(static function(callable $handler) use ($request) {
            return function(RequestInterface $psrRequest, array $options) use ($handler, $request) {
                return $handler($psrRequest, $options)->then(function($response) use ($request, $psrRequest) {
                    return $request->getResult($response)->setPsrRequest($psrRequest);
                });
            };
        });

        self::$client_config['handler'] = $stack;

        return new Client(self::$client_config);
    }

    /**
     * @throws ClientException
     * @throws Exception
     */
    private function response()
    {
        try {
            return $this->getHttpClient()->request(
                $this->method,
                (string)$this->uri,
                $this->options
            );
        } catch (GuzzleException $exception) {
            if ($this->shouldClientRetry($exception)) {
                return $this->response();
            }
            throw new ClientException(
                $exception->getMessage(),
                SDK::SERVER_UNREACHABLE,
                $exception
            );
        }
    }

    /**
     * Remove redundant Query
     *
     * @codeCoverageIgnore
     */
    private function cleanQuery()
    {
        if (isset($this->options['query']) && $this->options['query'] === []) {
            unset($this->options['query']);
        }
    }

    /**
     * Remove redundant Headers
     *
     * @codeCoverageIgnore
     */
    private function cleanFormParams()
    {
        if (isset($this->options['form_params']) && $this->options['form_params'] === []) {
            unset($this->options['form_params']);
        }
    }
}