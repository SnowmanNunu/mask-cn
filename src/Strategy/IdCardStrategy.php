<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

class IdCardStrategy implements StrategyInterface
{
    /**
     * 中国身份证号脱敏
     *
     *  - 18 位: 110101199003078888 -> 110101********8888
     *  - 15 位: 110101900307888    -> 110101******888
     *
     * @param array<string, mixed> $options ['char' => '*']
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options['char']) ? (string) $options['char'] : '*';
        $input = strtoupper(trim($input));
        $len = strlen($input);

        if ($len === 18) {
            return substr($input, 0, 6) . str_repeat($char, 8) . substr($input, -4);
        }

        if ($len === 15) {
            return substr($input, 0, 6) . str_repeat($char, 6) . substr($input, -3);
        }

        // 非标准长度,通用回退
        if ($len <= 4) {
            return $input;
        }
        $front = (int) ceil($len / 4);
        $back = (int) ceil($len / 4);
        $maskLen = max(0, $len - $front - $back);
        return substr($input, 0, $front) . str_repeat($char, $maskLen) . substr($input, -$back);
    }
}
