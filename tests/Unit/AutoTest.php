<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use PHPUnit\Framework\TestCase;

class AutoTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_phone_in_text(): void
    {
        $text = '我的电话是 13812345678 别打';
        $this->assertSame('我的电话是 138****5678 别打', Mask::auto($text));
    }

    /** @test */
    public function it_masks_email_in_text(): void
    {
        $text = '联系我 foo@example.com 谢谢';
        $this->assertSame('联系我 f**@example.com 谢谢', Mask::auto($text));
    }

    /** @test */
    public function it_masks_id_card_in_text(): void
    {
        $text = '身份证 110101199003078888 已上传';
        $this->assertSame('身份证 110101********8888 已上传', Mask::auto($text));
    }

    /** @test */
    public function it_masks_only_specified_types(): void
    {
        $text = '电话 13812345678,邮箱 foo@bar.com';
        $result = Mask::auto($text, ['phone']);
        $this->assertStringContainsString('138****5678', $result);
        $this->assertStringContainsString('foo@bar.com', $result);
    }
}
