<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

class TaiwanIdStrategy implements StrategyInterface
{
    /**
     * 台湾居民身份证 / 台湾居民居住证 脱敏
     *
     * - 台湾身份证: A123456789 → A1****789
     * - 台湾居住证(18位,830000开头): 83000019900101001X → 830000********001X
     *
     * @param array<string, mixed> $options ["char" => "*"]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : "*";
        $input = strtoupper(trim($input));

        // 台湾身份证: 1 位大写字母 + 9 位数字
        if (preg_match("/^[A-Z]\d{9}$/", $input)) {
            return substr($input, 0, 2) . str_repeat($char, 4) . substr($input, -3);
        }

        // 台湾居民居住证(18位)
        if (preg_match("/^830{4}\d{11}[\dX]$/", $input)) {
            return substr($input, 0, 6) . str_repeat($char, 8) . substr($input, -4);
        }

        // 通用回退
        $len = strlen($input);
        if ($len <= 4) {
            return $input;
        }
        $front = (int) ceil($len / 4);
        $back = (int) ceil($len / 4);
        $maskLen = max(0, $len - $front - $back);

        return substr($input, 0, $front) . str_repeat($char, $maskLen) . substr($input, -$back);
    }
}
