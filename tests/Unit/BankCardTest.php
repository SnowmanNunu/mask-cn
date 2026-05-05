<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use MaskCn\Strategy\BankCardStrategy;
use PHPUnit\Framework\TestCase;

class BankCardTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_19_digit_card(): void
    {
        $this->assertSame('6222***********0123', Mask::bankCard('6222021234567890123'));
    }

    /** @test */
    public function it_masks_16_digit_card(): void
    {
        $this->assertSame('6222********7890', Mask::bankCard('6222021234567890'));
    }

    /** @test */
    public function it_strips_internal_whitespace(): void
    {
        $this->assertSame('6222********7890', Mask::bankCard('6222 0212 3456 7890'));
    }

    /** @test */
    public function it_supports_space_option(): void
    {
        $masker = new BankCardStrategy();
        $this->assertSame('6222 **** **** 7890', $masker->mask('6222021234567890', ['space' => true]));
    }

    /** @test */
    public function it_returns_too_short_unchanged(): void
    {
        $this->assertSame('123456', Mask::bankCard('123456'));
    }
}
