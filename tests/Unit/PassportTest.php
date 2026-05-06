<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use MaskCn\Strategy\PassportStrategy;
use PHPUnit\Framework\TestCase;

class PassportTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_chinese_passport(): void
    {
        $this->assertSame('E****5678', Mask::passport('E12345678'));
    }

    /** @test */
    public function it_masks_passport_starting_with_g(): void
    {
        $this->assertSame('G****9012', Mask::passport('G12349012'));
    }

    /** @test */
    public function it_masks_alphanumeric_passport(): void
    {
        $this->assertSame('AB***XYZ', Mask::passport('AB123XYZ'));
    }

    /** @test */
    public function it_uses_custom_char(): void
    {
        $strategy = new PassportStrategy();
        $this->assertSame('P####5678', $strategy->mask('P12345678', ['char' => '#']));
    }

    /** @test */
    public function it_returns_short_input_unchanged(): void
    {
        $this->assertSame('123', Mask::passport('123'));
    }

    /** @test */
    public function it_returns_empty_string_unchanged(): void
    {
        $this->assertSame('', Mask::passport(''));
    }

    /** @test */
    public function it_handles_pure_numeric(): void
    {
        $this->assertSame('12***678', Mask::passport('12345678'));
    }

    /** @test */
    public function it_handles_pure_alphabetic(): void
    {
        $this->assertSame('AB***FGH', Mask::passport('ABCDEFGH'));
    }

    /** @test */
    public function it_handles_short_alphanumeric(): void
    {
        $this->assertSame('E123', Mask::passport('E123'));
    }

    /** @test */
    public function it_handles_special_chars(): void
    {
        $this->assertSame('E12!', Mask::passport('E12!'));
    }

    /** @test */
    public function it_handles_multibyte_char(): void
    {
        $strategy = new PassportStrategy();
        $this->assertSame('E※※※※5678', $strategy->mask('E12345678', ['char' => '※']));
    }
}
