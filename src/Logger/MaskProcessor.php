<?php

declare(strict_types=1);

namespace MaskCn\Logger;

use MaskCn\Auto;

/**
 * 日志/消息处理器: 对字符串进行自动脱敏
 *
 * 不依赖 PSR-3,可在任何日志框架或中间件中使用:
 *
 * ```php
 * $message = MaskProcessor::process('用户 phone: 13812345678');
 * // '用户 phone: 138****5678'
 * ```
 */
class MaskProcessor
{
    /** @var Auto|null */
    private static $auto = null;

    public static function process(string $message): string
    {
        if (self::$auto === null) {
            self::$auto = new Auto();
        }
        return self::$auto->mask($message);
    }
}
