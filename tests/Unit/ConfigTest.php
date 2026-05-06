<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    protected function tearDown(): void
    {
        Config::reset();
    }

    /** @test */
    public function it_sets_and_gets_a_value(): void
    {
        Config::set(['char' => '#']);
        $this->assertSame('#', Config::get('char'));
    }

    /** @test */
    public function it_returns_default_when_key_missing(): void
    {
        $this->assertNull(Config::get('missing'));
        $this->assertSame('default', Config::get('missing', 'default'));
    }

    /** @test */
    public function it_overwrites_previous_config(): void
    {
        Config::set(['foo' => 'bar']);
        Config::set(['baz' => 'qux']);
        $this->assertNull(Config::get('foo'));
        $this->assertSame('qux', Config::get('baz'));
    }

    /** @test */
    public function it_resets_config(): void
    {
        Config::set(['char' => '#']);
        Config::reset();
        $this->assertNull(Config::get('char'));
    }

    /** @test */
    public function it_affects_auto_masking_char(): void
    {
        Config::set(['char' => '#']);
        $result = \MaskCn\Mask::auto('电话 13812345678');
        $this->assertSame('电话 138####5678', $result);
    }

    /** @test */
    public function it_affects_strategy_masking_char(): void
    {
        Config::set(['char' => '#']);
        $result = \MaskCn\Mask::phone('13812345678');
        $this->assertSame('138####5678', $result);
    }

    /** @test */
    public function options_override_config(): void
    {
        Config::set(['char' => '#']);
        $result = \MaskCn\Mask::phone('13812345678', ['char' => '*']);
        $this->assertSame('138****5678', $result);
    }
}
