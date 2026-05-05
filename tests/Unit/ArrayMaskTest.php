<?php

declare(strict_types=1);

namespace MaskCn\Tests\Unit;

use MaskCn\Mask;
use PHPUnit\Framework\TestCase;

class ArrayMaskTest extends TestCase
{
    protected function setUp(): void
    {
        Mask::reset();
    }

    /** @test */
    public function it_masks_array_by_rules(): void
    {
        $data = [
            'name' => '张小明',
            'phone' => '13812345678',
            'id_card' => '110101199003078888',
            'age' => 25,
        ];

        $rules = [
            'name' => 'name',
            'phone' => 'phone',
            'id_card' => 'idCard',
        ];

        $expected = [
            'name' => '张*明',
            'phone' => '138****5678',
            'id_card' => '110101********8888',
            'age' => 25,
        ];

        $this->assertSame($expected, Mask::array($data, $rules));
    }

    /** @test */
    public function it_keeps_unmapped_keys_unchanged(): void
    {
        $data = ['phone' => '13812345678', 'extra' => 'unchanged'];
        $result = Mask::array($data, ['phone' => 'phone']);

        $this->assertSame('138****5678', $result['phone']);
        $this->assertSame('unchanged', $result['extra']);
    }
}
