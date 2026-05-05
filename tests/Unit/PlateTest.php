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
}
