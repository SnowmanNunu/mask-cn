<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

class BankCardStrategy implements StrategyInterface
{
    /**
     * 国内银行卡号脱敏
     *
     *  - 输入会先去除内部空白,因此 "6222 0212 3456 7890 123" 等同 "6222021234567890123"
     *  - 默认输出无空格紧凑格式: 6222***********0123
     *  - 开启 space 选项后,按 4 位分组: "6222 **** **** ***0 123"
     *    (注意:19 位卡号末尾仅 3 字符,符合 UnionPay 4-4-4-4-3 显示惯例)
     *
     * @param array<string, mixed> $options ['char' => '*', 'space' => false]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options['char']) ? (string) $options['char'] : '*';
        $space = isset($options['space']) ? (bool) $options['space'] : false;

        $input = preg_replace('/\s+/', '', trim($input));
        $len = strlen($input);

        if ($len < 12) {
            return $input;
        }

        $masked = substr($input, 0, 4) . str_repeat($char, $len - 8) . substr($input, -4);

        if (!$space) {
            return $masked;
        }

        return trim(chunk_split($masked, 4, ' '));
    }
}
