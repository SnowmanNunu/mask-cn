<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Config;
use MaskCn\Mask;
use PHPUnit\Framework\TestCase;

class ConfigKeepTest extends TestCase
{
    protected function tearDown(): void
    {
        Config::reset();
    }

    /** @test */
    public function config_sets_phone_keep_front_back(): void
    {
        Config::set(['phone' => ['front' => 2, 'back' => 3]]);
        $result = Mask::phone('13812345678');
        $this->assertSame('13******678', $result);
    }

    /** @test */
    public function config_sets_idcard_keep_front_back(): void
    {
        Config::set(['idCard' => ['front' => 4, 'back' => 2]]);
        $result = Mask::idCard('110101199003078888');
        $this->assertSame('1101************88', $result);
    }

    /** @test */
    public function config_sets_bankcard_keep_front_back(): void
    {
        Config::set(['bankCard' => ['front' => 6, 'back' => 4]]);
        $result = Mask::bankCard('6222021234567890123');
        $this->assertSame('622202*********0123', $result);
    }

    /** @test */
    public function config_sets_plate_keep_front_back(): void
    {
        Config::set(['plate' => ['front' => 3, 'back' => 1]]);
        $result = Mask::plate('京A12345');
        $this->assertSame('京A1***5', $result);
    }

    /** @test */
    public function options_override_config_keep(): void
    {
        Config::set(['phone' => ['front' => 2, 'back' => 3]]);
        $result = Mask::phone('13812345678', ['front' => 3, 'back' => 4]);
        $this->assertSame('138****5678', $result);
    }

    /** @test */
    public function config_char_and_keep_work_together(): void
    {
        Config::set([
            'char' => '#',
            'phone' => ['front' => 2, 'back' => 3],
        ]);
        $result = Mask::phone('13812345678');
        $this->assertSame('13######678', $result);
    }
}
