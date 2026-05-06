<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_2_char_name(): void
    {
        $this->assertSame('张*', Mask::name('张三'));
    }

    /** @test */
    public function it_masks_3_char_single_surname(): void
    {
        $this->assertSame('张*明', Mask::name('张小明'));
    }

    /** @test */
    public function it_masks_3_char_compound_surname(): void
    {
        $this->assertSame('欧阳*', Mask::name('欧阳明'));
    }

    /** @test */
    public function it_masks_4_char_compound_surname(): void
    {
        $this->assertSame('欧阳*娜', Mask::name('欧阳娜娜'));
    }

    /** @test */
    public function it_masks_4_char_single_surname(): void
    {
        $this->assertSame('张**明', Mask::name('张小明明'));
    }

    /** @test */
    public function it_returns_single_char_unchanged(): void
    {
        $this->assertSame('张', Mask::name('张'));
    }

    /** @test */
    public function it_recognizes_compound_zhuge(): void
    {
        $this->assertSame('诸葛*明', Mask::name('诸葛孔明'));
    }

    /** @test */
    public function it_returns_empty_string_unchanged(): void
    {
        $this->assertSame('', Mask::name(''));
    }

    /** @test */
    public function it_handles_english_name(): void
    {
        $this->assertSame('J**n', Mask::name('John'));
    }

    /** @test */
    public function it_handles_long_name(): void
    {
        $this->assertSame('欧阳*****娜', Mask::name('欧阳娜娜欧阳娜娜'));
    }

    /** @test */
    public function it_handles_numeric_name(): void
    {
        $this->assertSame('1*3', Mask::name('123'));
    }

    /** @test */
    public function it_handles_multibyte_char(): void
    {
        $this->assertSame('张※', Mask::name('张三', ['char' => '※']));
    }
}
