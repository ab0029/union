<?php

namespace Young\Union\Traits;

use DateTime;
use DateTimeZone;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Trait LogTrait
 *
 * @package Young\Union\Traits
 */
trait LogTrait
{
    /**
     * @var LoggerInterface
     */
    private static $logger;

    /**
     * @var float
     */
    private static $logStartTime = 0;

    /**
     * @var string
     */
    private static $logFormat;

    /**
     * @var DateTime
     */
    private static $ts;

    /**
     * @return LoggerInterface
     */
    public static function getLogger()
    {
        return self::$logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @throws Exception
     */
    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger       = $logger;
        self::$logStartTime = microtime(true);
        $timezone           = new DateTimeZone(date_default_timezone_get() ?: 'UTC');
        if (PHP_VERSION_ID < 70100) {
            self::$ts = DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true)), $timezone);
        } else {
            self::$ts = new DateTime(null, $timezone);
        }
    }

    /**
     * @return string
     * {request}    完整的HTTP请求消息
     * {response}  完整的HTTP响应消息
     * {ts}    GMT中的 ISO 8601日期
     * {date_iso_8601} GMT中的 ISO 8601日期
     * {date_common_log}   使用配置的时区的Apache常用日志日期
     * {host}  请求主机
     * {method}    请求方法
     * {uri}   请求的URI
     * {version}   协议版本
     * {target}    请求目标 (path + query + fragment)
     * {hostname}  发送请求的计算机的主机名
     * {code}  响应的状态代码（如果可用）
     * {phrase}    响应的原因短语（如果有）
     * {error} 任何错误消息（如果有）
     * {req_header_*}  将 * 替换为请求标头的小写名称以添加到消息中
     * {res_header_*}  将 * 替换为响应头的小写名称以添加到消息中
     * {req_headers}   请求头
     * {res_headers}   响应头
     * {req_body}  请求主体
     * {res_body}  响应主体
     * {pid}   PID
     * {cost}  耗时
     * {start_time}    开始时间
    */
    public static function getLogFormat()
    {
        $template = self::$logFormat
            ?: '"{method} {uri} HTTP/{version}" {code} {req_body} {res_body}';

        return str_replace(
            ['{pid}', '{cost}', '{start_time}'],
            [getmypid(), self::getCost(), self::$ts->format('Y-m-d H:i:s.u')],
            $template
        );
    }

    /**
     * Apache Common Log Format.
     *
     * @param string $formatter
     *
     * @link http://httpd.apache.org/docs/2.4/logs.html#common
     * @see  \GuzzleHttp\MessageFormatter
     */
    public static function setLogFormat($formatter)
    {
        self::$logFormat = $formatter;
    }

    /**
     * @return float|mixed
     */
    private static function getCost()
    {
        return microtime(true) - self::$logStartTime;
    }
}
