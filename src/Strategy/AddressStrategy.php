<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

use MaskCn\Config;

class AddressStrategy implements StrategyInterface
{
    /** @var string[] */
    private $municipalities = ["北京", "上海", "天津", "重庆"];

    /**
     * 中文地址脱敏: 保留省/直辖市 + 市, 掩码区及详细地址
     *
     * - 广东省深圳市南山区科技园 → 广东省深圳市********
     * - 北京市朝阳区建国路 → 北京市******
     * - 短地址(≤4字)原样返回
     *
     * @param array<string, mixed> $options ["char" => "*"]
     */
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : Config::get("char", "*");
        $input = trim($input);

        // 直辖市: 保留到 "市" 为止
        foreach ($this->municipalities as $muni) {
            if (strpos($input, $muni) === 0) {
                $pos = strpos($input, "市");
                if ($pos !== false) {
                    $preserve = substr($input, 0, $pos + 3);
                    $maskPart = substr($input, $pos + 3);
                    return $preserve . str_repeat($char, mb_strlen($maskPart));
                }
                // 没有 "市" 字,保留直辖市名本身
                $muniByteLen = strlen($muni);
                $preserve = substr($input, 0, $muniByteLen);
                $maskPart = substr($input, $muniByteLen);
                return $preserve . str_repeat($char, mb_strlen($maskPart));
            }
        }

        // 省/自治区/特别行政区
        $provinceEnd = 0;
        if (($pos = strpos($input, "省")) !== false) {
            $provinceEnd = $pos + 3;
        } elseif (($pos = strpos($input, "自治区")) !== false) {
            $provinceEnd = $pos + 9;
        } elseif (($pos = strpos($input, "特别行政区")) !== false) {
            $provinceEnd = $pos + 15;
        }

        // 在市/州/盟/地区之后截断
        $rest = substr($input, $provinceEnd);
        $cityEnd = 0;
        if (($pos = strpos($rest, "市")) !== false) {
            $cityEnd = $pos + 3;
        } elseif (($pos = strpos($rest, "州")) !== false) {
            $cityEnd = $pos + 3;
        } elseif (($pos = strpos($rest, "盟")) !== false) {
            $cityEnd = $pos + 3;
        } elseif (($pos = strpos($rest, "地区")) !== false) {
            $cityEnd = $pos + 6;
        }

        if ($provinceEnd > 0 && $cityEnd > 0) {
            $preserveLen = $provinceEnd + $cityEnd;
            $preserve = substr($input, 0, $preserveLen);
            $maskPart = substr($input, $preserveLen);
            return $preserve . str_repeat($char, mb_strlen($maskPart));
        }

        // 仅识别到省/自治区/特别行政区
        if ($provinceEnd > 0) {
            $preserve = substr($input, 0, $provinceEnd);
            $maskPart = substr($input, $provinceEnd);
            return $preserve . str_repeat($char, mb_strlen($maskPart));
        }

        // 通用回退: 保留前 1/4, 掩码剩余部分
        $len = mb_strlen($input);
        if ($len <= 4) {
            return $input;
        }
        $front = (int) ceil($len / 4);
        $maskLen = max(0, $len - $front);
        return mb_substr($input, 0, $front) . str_repeat($char, $maskLen);
    }
}
