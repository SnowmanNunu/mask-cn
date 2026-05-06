<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

use MaskCn\Config;

use MaskCn\Helper\ChineseName;

class NameStrategy implements StrategyInterface
{
    /**
     * 中文姓名脱敏 (自动识别复姓)
     *
     * 规则:
     *   - 1 字: 张      -> 张        (太短不脱敏)
     *   - 2 字: 张三    -> 张*
     *   - 3 字 单姓: 张小明 -> 张*明
     *   - 3 字 复姓: 欧阳明 -> 欧阳*
     *   - 4 字 单姓: 张小明明 -> 张**明
     *   - 4 字 复姓: 欧阳娜娜 -> 欧阳*娜
     *   - 5+ 字: 同样保留首尾,中间掩码
     *
     * @param array<string, mixed> $options ['char' => '*']
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : Config::get("char", "*");
        $input = trim($input);
        $len = mb_strlen($input);

        if ($len < 2) {
            return $input;
        }

        if ($len === 2) {
            return mb_substr($input, 0, 1) . $char;
        }

        $isCompound = $len >= 3 && ChineseName::isCompoundSurname(mb_substr($input, 0, 2));

        if ($len === 3) {
            if ($isCompound) {
                return mb_substr($input, 0, 2) . $char;
            }
            return mb_substr($input, 0, 1) . $char . mb_substr($input, -1);
        }

        // 4+ 字
        $prefixLen = $isCompound ? 2 : 1;
        $prefix = mb_substr($input, 0, $prefixLen);
        $tail = mb_substr($input, -1);
        $maskCount = $len - $prefixLen - 1;
        return $prefix . str_repeat($char, $maskCount) . $tail;
    }
}
