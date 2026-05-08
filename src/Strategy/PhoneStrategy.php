<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

use MaskCn\Config;

class PhoneStrategy implements StrategyInterface
{
    /**
     * 中国大陆手机号脱敏: 13812345678 -> 138****5678
     *
     * @param array<string, mixed> $options ['char' => '*', 'front' => 3, 'back' => 4]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : Config::get("char", "*");
        $front = isset($options["front"]) ? (int) $options["front"] : 3;
        $back = isset($options["back"]) ? (int) $options["back"] : 4;
        $input = trim($input);

        // 标准 11 位手机号(1开头, 第二位 3-9)
        if (preg_match('/^1[3-9]\d{9}$/', $input)) {
            return substr($input, 0, $front) . str_repeat($char, max(0, 11 - $front - $back)) . substr($input, -$back);
        }

        // 非标准格式回退:保留前3后4
        $len = strlen($input);
        if ($len <= $front + $back) {
            return $input;
        }
        return substr($input, 0, $front) . str_repeat($char, $len - $front - $back) . substr($input, -$back);
    }
}
