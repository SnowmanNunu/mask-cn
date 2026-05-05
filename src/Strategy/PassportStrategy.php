<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

class PassportStrategy implements StrategyInterface
{
    /**
     * 护照号码脱敏
     *
     * - 中国普通护照: E12345678 / G12345678 → E****5678
     * - 其他字母+数字组合: 保留前2后3,中间脱敏
     *
     * @param array<string, mixed> $options ["char" => "*"]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : "*";
        $input = strtoupper(trim($input));
        $len = strlen($input);

        // 中国护照常见格式: 字母 + 8 位数字
        if (preg_match("/^[A-Z]\d{8}$/", $input)) {
            return $input[0] . str_repeat($char, 4) . substr($input, -4);
        }

        // 其他字母数字混合护照号(至少 6 位)
        if ($len >= 6 && preg_match("/^[A-Z0-9]+$/", $input)) {
            $front = 2;
            $back = 3;
            $maskLen = max(0, $len - $front - $back);
            return substr($input, 0, $front) . str_repeat($char, $maskLen) . substr($input, -$back);
        }

        // 通用回退
        if ($len <= 4) {
            return $input;
        }
        $front = (int) ceil($len / 4);
        $back = (int) ceil($len / 4);
        $maskLen = max(0, $len - $front - $back);

        return substr($input, 0, $front) . str_repeat($char, $maskLen) . substr($input, -$back);
    }
}
