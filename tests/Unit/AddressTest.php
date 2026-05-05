<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use MaskCn\Strategy\AddressStrategy;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_province_city_address(): void
    {
        $this->assertSame("广东省深圳市******", Mask::address("广东省深圳市南山区科技园"));
    }

    /** @test */
    public function it_masks_municipality_address(): void
    {
        $this->assertSame("北京市******", Mask::address("北京市朝阳区建国路"));
    }

    /** @test */
    public function it_masks_autonomous_region_address(): void
    {
        $this->assertSame("广西壮族自治区南宁市*******", Mask::address("广西壮族自治区南宁市青秀区民族大道"));
    }

    /** @test */
    public function it_masks_special_admin_region(): void
    {
        $this->assertSame("香港特别行政区*****", Mask::address("香港特别行政区九龙尖沙咀"));
    }

    /** @test */
    public function it_masks_with_city_only(): void
    {
        $this->assertSame("深圳市******", Mask::address("深圳市南山区科技园"));
    }

    /** @test */
    public function it_uses_custom_char(): void
    {
        $strategy = new AddressStrategy();
        $this->assertSame("广东省深圳市######", $strategy->mask("广东省深圳市南山区科技园", ["char" => "#"]));
    }

    /** @test */
    public function it_returns_short_address_unchanged(): void
    {
        $this->assertSame("南山区", Mask::address("南山区"));
    }

    /** @test */
    public function it_handles_municipality_without_shi(): void
    {
        $this->assertSame("北京***", Mask::address("北京朝阳区"));
    }
}
