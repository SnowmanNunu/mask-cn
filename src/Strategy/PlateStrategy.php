<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

use MaskCn\Config;

class PlateStrategy implements StrategyInterface
{
    /**
     * 中国车牌号脱敏
     *
     *  - 普通燃油车 7 位: 京A12345 -> 京A***45
     *  - 新能源 8 位:    京AD12345 -> 京A****45
     *
     * 规则:保留首 2 位 (省份简称 + 字母),保留末 2 位,中间脱敏
     *
     * @param array<string, mixed> $options ['char' => '*', 'front' => 2, 'back' => 2]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : Config::get("char", "*");
        $front = isset($options["front"]) ? (int) $options["front"] : 2;
        $back = isset($options["back"]) ? (int) $options["back"] : 2;
        $input = trim($input);
        $len = mb_strlen($input);

        if ($len < $front + $back + 1) {
            return $input;
        }

        $prefix = mb_substr($input, 0, $front);
        $tail = mb_substr($input, -$back);
        $maskCount = max(0, $len - $front - $back);
        return $prefix . str_repeat($char, $maskCount) . $tail;
    }
}
