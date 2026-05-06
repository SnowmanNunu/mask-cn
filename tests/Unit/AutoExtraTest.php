<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use PHPUnit\Framework\TestCase;

class AutoExtraTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_plate_in_text(): void
    {
        $text = '车牌 京A12345 已登记';
        $this->assertSame('车牌 京A***45 已登记', Mask::auto($text));
    }

    /** @test */
    public function it_masks_new_energy_plate_in_text(): void
    {
        $text = '新能源 京AD12345';
        $this->assertSame('新能源 京A****45', Mask::auto($text));
    }

    /** @test */
    public function it_masks_passport_in_text(): void
    {
        $text = '护照号 E12345678 已录入';
        $this->assertSame('护照号 E****5678 已录入', Mask::auto($text));
    }

    /** @test */
    public function it_masks_hk_pass_in_text(): void
    {
        $text = '回乡证 H12345678 有效';
        $this->assertSame('回乡证 H****5678 有效', Mask::auto($text));
    }

    /** @test */
    public function it_masks_taiwan_id_in_text(): void
    {
        $text = '台胞证 A123456789';
        $this->assertSame('台胞证 A1****789', Mask::auto($text));
    }

    /** @test */
    public function it_masks_uscc_in_text(): void
    {
        $text = '信用代码 91110105MA00XXXXXX';
        $this->assertSame('信用代码 91110105********XX', Mask::auto($text));
    }

    /** @test */
    public function it_masks_mixed_types_in_text(): void
    {
        $text = '电话13812345678,车牌京A12345,护照E12345678';
        $result = Mask::auto($text);
        $this->assertStringContainsString('138****5678', $result);
        $this->assertStringContainsString('京A***45', $result);
        $this->assertStringContainsString('E****5678', $result);
    }

    /** @test */
    public function it_limits_types_by_option(): void
    {
        $text = '电话13812345678,车牌京A12345';
        $result = Mask::auto($text, ['types' => ['plate']]);
        $this->assertStringContainsString('13812345678', $result);
        $this->assertStringContainsString('京A***45', $result);
    }

    /** @test */
    public function it_masks_new_types_in_json(): void
    {
        $json = json_encode([
            'vehicle' => ['plate' => '京A12345'],
            'travel' => ['passport' => 'E12345678'],
        ]);
        $result = Mask::auto($json);
        $decoded = json_decode($result, true);
        $this->assertSame('京A***45', $decoded['vehicle']['plate']);
        $this->assertSame('E****5678', $decoded['travel']['passport']);
    }
}
