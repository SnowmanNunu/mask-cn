<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_normal_email(): void
    {
        $this->assertSame('f**@example.com', Mask::email('foo@example.com'));
    }

    /** @test */
    public function it_masks_long_local_part(): void
    {
        $this->assertSame('h**********@gmail.com', Mask::email('hellomylove@gmail.com'));
    }

    /** @test */
    public function it_returns_invalid_email_unchanged(): void
    {
        $this->assertSame('not-an-email', Mask::email('not-an-email'));
    }

    /** @test */
    public function it_returns_empty_string_unchanged(): void
    {
        $this->assertSame('', Mask::email(''));
    }

    /** @test */
    public function it_handles_no_at_sign(): void
    {
        $this->assertSame('fooexample.com', Mask::email('fooexample.com'));
    }

    /** @test */
    public function it_handles_multiple_at_signs(): void
    {
        $this->assertSame('f******@baz.com', Mask::email('foo@bar@baz.com'));
    }

    /** @test */
    public function it_handles_whitespace_only(): void
    {
        $this->assertSame('', Mask::email(''));
    }

    /** @test */
    public function it_handles_single_char_local(): void
    {
        $this->assertSame('a*@test.com', Mask::email('ab@test.com'));
    }

    /** @test */
    public function it_handles_multibyte_char(): void
    {
        $this->assertSame('f※※@example.com', Mask::email('foo@example.com', ['char' => '※']));
    }
}
