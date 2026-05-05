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
        $result = Mask::auto($text, ['types' => ['phone']]);
        $this->assertStringContainsString('138****5678', $result);
        $this->assertStringContainsString('foo@bar.com', $result);
    }

    /** @test */
    public function it_masks_with_custom_char(): void
    {
        $text = '电话 13812345678';
        $this->assertSame('电话 138####5678', Mask::auto($text, ['char' => '#']));
    }

    /** @test */
    public function it_masks_json_string(): void
    {
        $json = json_encode([
            'user' => [
                'phone' => '13812345678',
                'idCard' => '110101199003078888',
            ],
            'email' => 'foo@bar.com',
        ]);
        $result = Mask::auto($json);
        $decoded = json_decode($result, true);
        $this->assertSame('138****5678', $decoded['user']['phone']);
        $this->assertSame('110101********8888', $decoded['user']['idCard']);
        $this->assertSame('f**@bar.com', $decoded['email']);
    }

    /** @test */
    public function it_masks_json_with_custom_char(): void
    {
        $json = json_encode(['phone' => '13812345678']);
        $result = Mask::auto($json, ['char' => '#']);
        $decoded = json_decode($result, true);
        $this->assertSame('138####5678', $decoded['phone']);
    }

    /** @test */
    public function it_preserves_non_string_values_in_json(): void
    {
        $json = json_encode([
            'phone' => '13812345678',
            'age' => 25,
            'active' => true,
            'score' => 98.5,
        ]);
        $result = Mask::auto($json);
        $decoded = json_decode($result, true);
        $this->assertSame('138****5678', $decoded['phone']);
        $this->assertSame(25, $decoded['age']);
        $this->assertTrue($decoded['active']);
        $this->assertSame(98.5, $decoded['score']);
    }

    /** @test */
    public function it_masks_nested_json(): void
    {
        $json = json_encode([
            'data' => [
                'list' => [
                    ['phone' => '13812345678'],
                ],
            ],
        ]);
        $result = Mask::auto($json);
        $decoded = json_decode($result, true);
        $this->assertSame('138****5678', $decoded['data']['list'][0]['phone']);
    }
}
