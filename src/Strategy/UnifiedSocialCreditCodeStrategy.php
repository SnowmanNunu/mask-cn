<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

use MaskCn\Config;

class UnifiedSocialCreditCodeStrategy implements StrategyInterface
{
    /**
     * 统一社会信用代码 / 组织机构代码 / 营业执照 脱敏
     *
     * - 18 位 USCC: 91110105MA00xxxxxx → 91110105********xx
     * - 9  位 OrgCode: 123456789      → 123****89 (支持 12345678-9 格式)
     * - 15 位旧版营业执照: 110101123456789 → 110101******789
     *
     * @param array<string, mixed> $options ["char" => "*"]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : Config::get("char", "*");
        $input = strtoupper(trim($input));

        // 9 位组织机构代码(可能带横线,如 12345678-9)
        if (preg_match("/^\d{8}-?[0-9X]$/", $input)) {
            $normalized = str_replace("-", "", $input);
            return substr($normalized, 0, 3) . str_repeat($char, 4) . substr($normalized, -2);
        }

        // 18 位统一社会信用代码
        if (preg_match("/^[0-9A-Z]{18}$/", $input)) {
            return substr($input, 0, 8) . str_repeat($char, 8) . substr($input, -2);
        }

        // 15 位旧版营业执照
        if (preg_match("/^\d{15}$/", $input)) {
            return substr($input, 0, 6) . str_repeat($char, 6) . substr($input, -3);
        }

        // 非标准长度通用回退
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
