<?php

declare(strict_types=1);

namespace MaskCn\Helper;

class IdCardHelper
{
    /**
     * 校验中国 18 位身份证号校验位是否正确
     *
     *  - 加权因子: 7 9 10 5 8 4 2 1 6 3 7 9 10 5 8 4 2
     *  - 校验位映射: sum % 11 -> 1 0 X 9 8 7 6 5 4 3 2
     */
    public static function validate(string $id): bool
    {
        $id = strtoupper(trim($id));
        if (!preg_match('/^\d{17}[\dX]$/', $id)) {
            return false;
        }

        $weights = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $codes = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $sum += ((int) $id[$i]) * $weights[$i];
        }

        return $codes[$sum % 11] === substr($id, 17, 1);
    }

    /**
     * 是否为 15 位老身份证
     */
    public static function is15(string $id): bool
    {
        return (bool) preg_match('/^\d{15}$/', trim($id));
    }
}
