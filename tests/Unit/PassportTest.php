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
        $this->assertSame("E****5678", Mask::passport("E12345678"));
    }

    /** @test */
    public function it_masks_passport_starting_with_g(): void
    {
        $this->assertSame("G****9012", Mask::passport("G12349012"));
    }

    /** @test */
    public function it_masks_alphanumeric_passport(): void
    {
        $this->assertSame("AB***XYZ", Mask::passport("AB123XYZ"));
    }

    /** @test */
    public function it_uses_custom_char(): void
    {
        $strategy = new PassportStrategy();
        $this->assertSame("P####5678", $strategy->mask("P12345678", ["char" => "#"]));
    }

    /** @test */
    public function it_returns_short_input_unchanged(): void
    {
        $this->assertSame("123", Mask::passport("123"));
    }
}
