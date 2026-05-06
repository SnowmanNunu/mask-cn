<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

use MaskCn\Config;

class EmailStrategy implements StrategyInterface
{
    /**
     * 邮箱脱敏: foo@example.com -> f**@example.com
     *
     * 保留首字母,本地部分其余替换为 *
     *
     * @param array<string, mixed> $options ['char' => '*']
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : Config::get("char", "*");
        $input = trim($input);

        $atPos = strrpos($input, '@');
        if ($atPos === false || $atPos === 0) {
            return $input;
        }

        $local = substr($input, 0, $atPos);
        $domain = substr($input, $atPos);
        $localLen = mb_strlen($local);

        if ($localLen <= 1) {
            return str_repeat($char, 2) . $domain;
        }

        return mb_substr($local, 0, 1) . str_repeat($char, $localLen - 1) . $domain;
    }
}
