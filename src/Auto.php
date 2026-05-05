<?php

declare(strict_types=1);

namespace MaskCn;

class Auto
{
    /**
     * 智能识别并脱敏敏感数据
     *
     * 支持纯文本和 JSON 字符串:
     * - 纯文本: 内置识别身份证、手机号、邮箱、银行卡
     * - JSON: 递归遍历所有字符串值进行识别脱敏
     *
     * @param array<string, mixed> $options ["char" => "*", "types" => []]
     */
    public function mask(string $text, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : "*";
        $types = isset($options["types"]) ? (array) $options["types"] : [];

        // 检测是否是 JSON
        $decoded = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $masked = $this->maskArrayRecursive($decoded, $char, $types);
            return json_encode($masked, JSON_UNESCAPED_UNICODE);
        }

        return $this->maskText($text, $char, $types);
    }

    /**
     * @param array<int|string, mixed> $data
     * @return array<int|string, mixed>
     */
    private function maskArrayRecursive(array $data, string $char, array $types): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->maskArrayRecursive($value, $char, $types);
            } elseif (is_string($value)) {
                $result[$key] = $this->maskText($value, $char, $types);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    private function maskText(string $text, string $char, array $types): string
    {
        $masker = new Masker();
        $detectors = $this->buildDetectors($masker);

        if (!empty($types)) {
            $detectors = array_intersect_key($detectors, array_flip($types));
        }

        foreach ($detectors as $detector) {
            $text = preg_replace_callback(
                $detector["pattern"],
                static function (array $m) use ($detector, $char): string {
                    return $detector["masker"]($m, $char);
                },
                $text
            );
        }

        return $text;
    }

    /**
     * 顺序很重要:先识别 idCard (含校验位 X),再 phone,再 email,最后 bankCard
     * 因为 bankCard 16-19 位与 idCard 18 位有重叠,先标记的不会被二次匹配
     *
     * @return array<string, array{pattern: string, masker: callable}>
     */
    private function buildDetectors(Masker $masker): array
    {
        return [
            "idCard" => [
                "pattern" => "/(?<!\d)\d{17}[\dXx](?!\d)/",
                "masker" => static function (array $m, string $char) use ($masker): string {
                    return $masker->idCard($m[0], ["char" => $char]);
                },
            ],
            "phone" => [
                "pattern" => "/(?<!\d)1[3-9]\d{9}(?!\d)/",
                "masker" => static function (array $m, string $char) use ($masker): string {
                    return $masker->phone($m[0], ["char" => $char]);
                },
            ],
            "email" => [
                "pattern" => "/[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}/",
                "masker" => static function (array $m, string $char) use ($masker): string {
                    return $masker->email($m[0], ["char" => $char]);
                },
            ],
            "bankCard" => [
                "pattern" => "/(?<!\d)\d{16,19}(?!\d)/",
                "masker" => static function (array $m, string $char) use ($masker): string {
                    return $masker->bankCard($m[0], ["char" => $char, "space" => false]);
                },
            ],
        ];
    }
}
