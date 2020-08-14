<?php

namespace Young\Union;

class Application
{
    use Traits\MockTrait;
    use Traits\HistoryTrait;
    use Traits\LogTrait;
    use Traits\UserAgentTrait;

    const VERSION = '1.0.0';

    public static function make($product, $config)
    {
        $product = \ucfirst($product);

        $product_class = '\\Young' . '\\Union\\Clients\\' . $product . '\\Application';

        if (\class_exists($product_class)) {
            return new $product_class($config);
        }

        throw new Exceptions\ClientException(
            "May not yet support product $product quick access",
            SDK::SERVICE_NOT_FOUND
        );
    }

    public static function __callStatic($product, $arguments)
    {
        return self::make($product, ...$arguments);
    }
}