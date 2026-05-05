<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

class HkMacaoPassStrategy implements StrategyInterface
{
    /**
     * 港澳居民来往内地通行证(回乡证) / 港澳居住证 脱敏
     *
     * - 回乡证: H12345678 / M12345678 → H****5678
     * - 港澳居住证(18位,810000/820000开头): 81000019900101001X → 810000********001X
     *
     * @param array<string, mixed> $options ["char" => "*"]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : "*";
        $input = strtoupper(trim($input));

        // 回乡证: H/M + 8 位数字
        if (preg_match("/^[HM]\d{8}$/", $input)) {
            return $input[0] . str_repeat($char, 4) . substr($input, -4);
        }

        // 港澳居民居住证(18位)
        if (preg_match("/^8[12]0{4}\d{11}[\dX]$/", $input)) {
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
