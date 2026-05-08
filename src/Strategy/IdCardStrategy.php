<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

use MaskCn\Config;

class IdCardStrategy implements StrategyInterface
{
    /**
     * 中国身份证号脱敏
     *
     *  - 18 位: 110101199003078888 -> 110101********8888
     *  - 15 位: 110101900307888    -> 110101******888
     *
     * @param array<string, mixed> $options ['char' => '*', 'front' => 6, 'back' => 4]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : Config::get("char", "*");
        $front = isset($options["front"]) ? (int) $options["front"] : 6;
        $back = isset($options["back"]) ? (int) $options["back"] : 4;
        $input = strtoupper(trim($input));
        $len = strlen($input);

        if ($len === 18) {
            return substr($input, 0, $front) . str_repeat($char, max(0, 18 - $front - $back)) . substr($input, -$back);
        }

        if ($len === 15) {
            return substr($input, 0, 6) . str_repeat($char, 6) . substr($input, -3);
        }

        // 非标准长度,通用回退
        if ($len <= 4) {
            return $input;
        }
        $frontFallback = (int) ceil($len / 4);
        $backFallback = (int) ceil($len / 4);
        $maskLen = max(0, $len - $frontFallback - $backFallback);
        return substr($input, 0, $frontFallback) . str_repeat($char, $maskLen) . substr($input, -$backFallback);
    }
}
