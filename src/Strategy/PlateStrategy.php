<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

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
     * @param array<string, mixed> $options ['char' => '*']
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options['char']) ? (string) $options['char'] : '*';
        $input = trim($input);
        $len = mb_strlen($input);

        if ($len < 5) {
            return $input;
        }

        $prefix = mb_substr($input, 0, 2);
        $tail = mb_substr($input, -2);
        $maskCount = $len - 4;
        return $prefix . str_repeat($char, $maskCount) . $tail;
    }
}
