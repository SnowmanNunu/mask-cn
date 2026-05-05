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
}
