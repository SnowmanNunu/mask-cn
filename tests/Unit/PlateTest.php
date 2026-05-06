<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use PHPUnit\Framework\TestCase;

class PlateTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_standard_plate(): void
    {
        $this->assertSame('京A***45', Mask::plate('京A12345'));
    }

    /** @test */
    public function it_masks_new_energy_plate(): void
    {
        $this->assertSame('京A****45', Mask::plate('京AD12345'));
    }

    /** @test */
    public function it_returns_empty_string_unchanged(): void
    {
        $this->assertSame('', Mask::plate(''));
    }

    /** @test */
    public function it_handles_invalid_format(): void
    {
        $this->assertSame('AB****45', Mask::plate('AB****45'));
    }

    /** @test */
    public function it_handles_short_plate(): void
    {
        $this->assertSame('京A1', Mask::plate('京A1'));
    }

    /** @test */
    public function it_handles_special_chars(): void
    {
        $this->assertSame('京A***4!', Mask::plate('京A1234!'));
    }

    /** @test */
    public function it_handles_multibyte_char(): void
    {
        $this->assertSame('京A※※※45', Mask::plate('京A12345', ['char' => '※']));
    }
}
