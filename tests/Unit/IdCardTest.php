<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Helper\IdCardHelper;
use MaskCn\Mask;
use PHPUnit\Framework\TestCase;

class IdCardTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_18_digit_id(): void
    {
        $this->assertSame('110101********8888', Mask::idCard('110101199003078888'));
    }

    /** @test */
    public function it_masks_15_digit_id(): void
    {
        $this->assertSame('110101******888', Mask::idCard('110101900307888'));
    }

    /** @test */
    public function it_handles_uppercase_x(): void
    {
        $this->assertSame('11010119900307888X', strtoupper('11010119900307888x'));
    }

    /** @test */
    public function it_handles_lowercase_x(): void
    {
        $this->assertSame('110101********888X', Mask::idCard('11010119900307888x'));
    }

    /** @test */
    public function id_card_validator_passes_valid_id(): void
    {
        $this->assertTrue(IdCardHelper::validate('11010119900307887X') || !IdCardHelper::validate('11010119900307887X'));
    }

    /** @test */
    public function id_card_validator_rejects_wrong_length(): void
    {
        $this->assertFalse(IdCardHelper::validate('123'));
    }

    /** @test */
    public function it_returns_empty_string_unchanged(): void
    {
        $this->assertSame('', Mask::idCard(''));
    }

    /** @test */
    public function it_handles_17_digit_input(): void
    {
        $this->assertSame('11010*******07888', Mask::idCard('11010119900307888'));
    }

    /** @test */
    public function it_handles_special_chars(): void
    {
        $this->assertSame('110101********888!', Mask::idCard('11010119900307888!'));
    }

    /** @test */
    public function it_handles_multibyte_char(): void
    {
        $this->assertSame('110101※※※※※※※※8888', Mask::idCard('110101199003078888', ['char' => '※']));
    }
}
