<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use MaskCn\Strategy\UnifiedSocialCreditCodeStrategy;
use PHPUnit\Framework\TestCase;

class UnifiedSocialCreditCodeTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_18_digit_uscc(): void
    {
        $this->assertSame("91110105********XX", Mask::uscc("91110105MA00XXXXXX"));
    }

    /** @test */
    public function it_masks_9_digit_org_code(): void
    {
        $this->assertSame("123****89", Mask::uscc("123456789"));
    }

    /** @test */
    public function it_masks_org_code_with_dash(): void
    {
        $this->assertSame("123****8X", Mask::uscc("12345678-X"));
    }

    /** @test */
    public function it_masks_15_digit_old_license(): void
    {
        $this->assertSame("110101******789", Mask::uscc("110101123456789"));
    }

    /** @test */
    public function it_uses_custom_char(): void
    {
        $strategy = new UnifiedSocialCreditCodeStrategy();
        $this->assertSame("91110105########XX", $strategy->mask("91110105MA00XXXXXX", ["char" => "#"]));
    }

    /** @test */
    public function it_returns_short_input_unchanged(): void
    {
        $this->assertSame("123", Mask::uscc("123"));
    }
}
