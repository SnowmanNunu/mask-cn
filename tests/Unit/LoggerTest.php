<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Logger\MaskProcessor;
use MaskCn\Logger\MaskSensitiveLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class TestLogger implements LoggerInterface
{
    use LoggerTrait;

    /** @var array<array{level: string, message: string|\Stringable, context: array}> */
    public $records = [];

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->records[] = ['level' => $level, 'message' => $message, 'context' => $context];
    }
}

class LoggerTest extends TestCase
{
    /** @test */
    public function mask_sensitive_logger_masks_phone(): void
    {
        $inner = new TestLogger();
        $logger = new MaskSensitiveLogger($inner);

        $logger->info('用户 phone: 13812345678');

        $this->assertSame('用户 phone: 138****5678', $inner->records[0]['message']);
    }

    /** @test */
    public function mask_sensitive_logger_masks_id_card(): void
    {
        $inner = new TestLogger();
        $logger = new MaskSensitiveLogger($inner);

        $logger->warning('身份证 110101199003078888 已上传');

        $this->assertSame('身份证 110101********8888 已上传', $inner->records[0]['message']);
    }

    /** @test */
    public function mask_sensitive_logger_masks_email(): void
    {
        $inner = new TestLogger();
        $logger = new MaskSensitiveLogger($inner);

        $logger->error('联系邮箱 foo@example.com');

        $this->assertSame('联系邮箱 f**@example.com', $inner->records[0]['message']);
    }

    /** @test */
    public function mask_sensitive_logger_passes_context(): void
    {
        $inner = new TestLogger();
        $logger = new MaskSensitiveLogger($inner);

        $logger->debug('msg', ['user_id' => 1]);

        $this->assertSame(['user_id' => 1], $inner->records[0]['context']);
    }

    /** @test */
    public function mask_sensitive_logger_passes_level(): void
    {
        $inner = new TestLogger();
        $logger = new MaskSensitiveLogger($inner);

        $logger->alert('msg');

        $this->assertSame('alert', $inner->records[0]['level']);
    }

    /** @test */
    public function mask_processor_masks_phone(): void
    {
        $result = MaskProcessor::process('电话 13812345678');
        $this->assertSame('电话 138****5678', $result);
    }

    /** @test */
    public function mask_processor_masks_id_card(): void
    {
        $result = MaskProcessor::process('身份证 110101199003078888');
        $this->assertSame('身份证 110101********8888', $result);
    }

    /** @test */
    public function mask_processor_returns_plain_text_unchanged(): void
    {
        $text = '这是一段普通文本';
        $this->assertSame($text, MaskProcessor::process($text));
    }
}
