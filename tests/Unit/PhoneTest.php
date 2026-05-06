<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use MaskCn\Strategy\PhoneStrategy;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_standard_phone(): void
    {
        $this->assertSame('138****5678', Mask::phone('13812345678'));
    }

    /** @test */
    public function it_masks_phone_with_custom_char(): void
    {
        $this->assertSame('138####5678', (new PhoneStrategy())->mask('13812345678', ['char' => '#']));
    }

    /** @test */
    public function it_returns_short_input_unchanged(): void
    {
        $this->assertSame('123', Mask::phone('123'));
    }

    /** @test */
    public function it_handles_phone_with_whitespace(): void
    {
        $this->assertSame('138****5678', Mask::phone('  13812345678  '));
    }

    /** @test */
    public function it_returns_empty_string_unchanged(): void
    {
        $this->assertSame('', Mask::phone(''));
    }

    /** @test */
    public function it_handles_non_numeric_input(): void
    {
        $this->assertSame('abc', Mask::phone('abc'));
    }

    /** @test */
    public function it_handles_letters_in_phone(): void
    {
        $this->assertSame('138****567a', Mask::phone('138****567a'));
    }

    /** @test */
    public function it_handles_very_long_input(): void
    {
        $this->assertSame('138***********2345', Mask::phone('138123456789012345'));
    }

    /** @test */
    public function it_returns_single_char_unchanged(): void
    {
        $this->assertSame('1', Mask::phone('1'));
    }

    /** @test */
    public function it_handles_multibyte_char(): void
    {
        $strategy = new PhoneStrategy();
        $this->assertSame('138※※※※5678', $strategy->mask('13812345678', ['char' => '※']));
    }
}
