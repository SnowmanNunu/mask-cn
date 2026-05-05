<?php

declare(strict_types=1);

namespace MaskCn;

class Auto
{
    /**
     * 在长文本中识别并脱敏敏感数据
     *
     * 内置识别:身份证、手机号、邮箱、银行卡
     *
     * @param string[] $types 限制识别类型,空数组表示识别所有
     */
    public function mask(string $text, array $types = []): string
    {
        $masker = new Masker();
        $detectors = $this->buildDetectors($masker);

        if (!empty($types)) {
            $detectors = array_intersect_key($detectors, array_flip($types));
        }

        foreach ($detectors as $detector) {
            $text = preg_replace_callback(
                $detector['pattern'],
                $detector['masker'],
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
            'idCard' => [
                'pattern' => '/(?<!\d)\d{17}[\dXx](?!\d)/',
                'masker' => static function (array $m) use ($masker): string {
                    return $masker->idCard($m[0]);
                },
            ],
            'phone' => [
                'pattern' => '/(?<!\d)1[3-9]\d{9}(?!\d)/',
                'masker' => static function (array $m) use ($masker): string {
                    return $masker->phone($m[0]);
                },
            ],
            'email' => [
                'pattern' => '/[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}/',
                'masker' => static function (array $m) use ($masker): string {
                    return $masker->email($m[0]);
                },
            ],
            'bankCard' => [
                'pattern' => '/(?<!\d)\d{16,19}(?!\d)/',
                'masker' => static function (array $m) use ($masker): string {
                    return $masker->bankCard($m[0], ['space' => false]);
                },
            ],
        ];
    }
}
