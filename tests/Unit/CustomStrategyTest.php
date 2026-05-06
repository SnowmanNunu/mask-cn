<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use MaskCn\Strategy\StrategyInterface;
use PHPUnit\Framework\TestCase;

class CustomStrategyTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_registers_and_calls_custom_strategy(): void
    {
        $strategy = new class implements StrategyInterface {
            public function mask(string $input, array $options = []): string
            {
                $char = isset($options["char"]) ? (string) $options["char"] : "*";
                return str_repeat($char, strlen($input));
            }
        };

        Mask::register("secret", $strategy);

        $this->assertSame("********", Mask::secret("password"));
    }

    /** @test */
    public function it_passes_options_to_custom_strategy(): void
    {
        $strategy = new class implements StrategyInterface {
            public function mask(string $input, array $options = []): string
            {
                $char = isset($options["char"]) ? (string) $options["char"] : "*";
                return str_repeat($char, strlen($input));
            }
        };

        Mask::register("secret", $strategy);

        $this->assertSame("########", Mask::secret("password", ["char" => "#"]));
    }

    /** @test */
    public function it_throws_for_unregistered_strategy(): void
    {
        $this->expectException(\BadMethodCallException::class);
        Mask::notExists("foo");
    }

    /** @test */
    public function it_preserves_builtin_methods(): void
    {
        $this->assertSame("138****5678", Mask::phone("13812345678"));
    }

    /** @test */
    public function it_works_with_mask_array(): void
    {
        $strategy = new class implements StrategyInterface {
            public function mask(string $input, array $options = []): string
            {
                return "CUSTOM";
            }
        };

        Mask::register("custom", $strategy);

        $result = Mask::array(["foo" => "bar"], ["foo" => "custom"]);
        $this->assertSame(["foo" => "CUSTOM"], $result);
    }
}
