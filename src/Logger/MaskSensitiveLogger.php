<?php

declare(strict_types=1);

namespace MaskCn\Logger;

use MaskCn\Auto;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * PSR-3 Logger 装饰器: 自动在日志消息输出前脱敏敏感字段
 *
 * 用法:
 * $maskLogger = new MaskSensitiveLogger($originalLogger);
 * $maskLogger->info('用户 phone: 13812345678'); // 实际记录: '用户 phone: 138****5678'
 */
class MaskSensitiveLogger implements LoggerInterface
{
    use LoggerTrait;

    /** @var LoggerInterface */
    private $inner;

    /** @var Auto|null */
    private $auto;

    public function __construct(LoggerInterface $inner)
    {
        $this->inner = $inner;
        $this->auto = new Auto();
    }

    /**
     * @param mixed $level
     * @param $message
     * @param array<string, mixed> $context
     */
    public function log($level, $message, array $context = []): void
    {
        if (is_string($message)) {
            $message = $this->auto->mask($message);
        }

        $this->inner->log($level, $message, $context);
    }
}
