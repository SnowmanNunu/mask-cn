<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use MaskCn\Strategy\HkMacaoPassStrategy;
use PHPUnit\Framework\TestCase;

class HkMacaoPassTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_hk_pass(): void
    {
        $this->assertSame('H****5678', Mask::hkMoPass('H12345678'));
    }

    /** @test */
    public function it_masks_macao_pass(): void
    {
        $this->assertSame('M****5678', Mask::hkMoPass('M12345678'));
    }

    /** @test */
    public function it_masks_hk_resident_permit(): void
    {
        $this->assertSame('810000********001X', Mask::hkMoPass('81000019900101001X'));
    }

    /** @test */
    public function it_masks_macao_resident_permit(): void
    {
        $this->assertSame('820000********002X', Mask::hkMoPass('82000019900101002X'));
    }

    /** @test */
    public function it_uses_custom_char(): void
    {
        $strategy = new HkMacaoPassStrategy();
        $this->assertSame('H####5678', $strategy->mask('H12345678', ['char' => '#']));
    }

    /** @test */
    public function it_returns_short_input_unchanged(): void
    {
        $this->assertSame('123', Mask::hkMoPass('123'));
    }

    /** @test */
    public function it_returns_empty_string_unchanged(): void
    {
        $this->assertSame('', Mask::hkMoPass(''));
    }

    /** @test */
    public function it_handles_lowercase_hm(): void
    {
        $this->assertSame('H****5678', Mask::hkMoPass('h12345678'));
    }

    /** @test */
    public function it_handles_invalid_first_letter(): void
    {
        $this->assertSame('X12***678', Mask::hkMoPass('X12***678'));
    }

    /** @test */
    public function it_handles_multibyte_char(): void
    {
        $strategy = new HkMacaoPassStrategy();
        $this->assertSame('H※※※※5678', $strategy->mask('H12345678', ['char' => '※']));
    }
}
