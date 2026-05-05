<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use MaskCn\Strategy\TaiwanIdStrategy;
use PHPUnit\Framework\TestCase;

class TaiwanIdTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_taiwan_id(): void
    {
        $this->assertSame("A1****789", Mask::taiwanId("A123456789"));
    }

    /** @test */
    public function it_masks_taiwan_resident_permit(): void
    {
        $this->assertSame("830000********001X", Mask::taiwanId("83000019900101001X"));
    }

    /** @test */
    public function it_uses_custom_char(): void
    {
        $strategy = new TaiwanIdStrategy();
        $this->assertSame("B1####789", $strategy->mask("B123456789", ["char" => "#"]));
    }

    /** @test */
    public function it_returns_short_input_unchanged(): void
    {
        $this->assertSame("123", Mask::taiwanId("123"));
    }
}
