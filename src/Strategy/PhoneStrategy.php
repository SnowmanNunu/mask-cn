<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

class PhoneStrategy implements StrategyInterface
{
    /**
     * 中国大陆手机号脱敏: 13812345678 -> 138****5678
     *
     * @param array<string, mixed> $options ['char' => '*']
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options['char']) ? (string) $options['char'] : '*';
        $input = trim($input);

        // 标准 11 位手机号(1开头, 第二位 3-9)
        if (preg_match('/^1[3-9]\d{9}$/', $input)) {
            return substr($input, 0, 3) . str_repeat($char, 4) . substr($input, -4);
        }

        // 非标准格式回退:保留前3后4
        $len = strlen($input);
        if ($len <= 7) {
            return $input;
        }
        return substr($input, 0, 3) . str_repeat($char, $len - 7) . substr($input, -4);
    }
}
