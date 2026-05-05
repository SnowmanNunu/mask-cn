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
}
