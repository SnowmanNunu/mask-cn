<?php

declare(strict_types=1);

namespace MaskCn\Helper;

class ChineseName
{
    /** @var array<int, string>|null */
    private static $compoundSurnames = null;

    /**
     * 判断 2 字字符串是否为复姓
     */
    public static function isCompoundSurname(string $candidate): bool
    {
        return in_array($candidate, self::compoundSurnames(), true);
    }

    /**
     * 获取复姓字典 (惰性加载)
     *
     * @return array<int, string>
     */
    public static function compoundSurnames(): array
    {
        if (self::$compoundSurnames === null) {
            self::$compoundSurnames = require __DIR__ . '/../data/compound_surnames.php';
        }
        return self::$compoundSurnames;
    }

    /**
     * 注册自定义复姓 (允许业务扩展)
     */
    public static function registerCompoundSurname(string $surname): void
    {
        $list = self::compoundSurnames();
        if (!in_array($surname, $list, true)) {
            $list[] = $surname;
            self::$compoundSurnames = $list;
        }
    }
}
