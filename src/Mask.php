<?php

declare(strict_types=1);

namespace MaskCn;

/**
 * 中文敏感数据脱敏 - 静态主入口
 *
 * @method static string phone(string $phone, array $options = [])
 * @method static string idCard(string $idCard, array $options = [])
 * @method static string bankCard(string $bankCard, array $options = [])
 * @method static string name(string $name, array $options = [])
 * @method static string email(string $email, array $options = [])
 * @method static string plate(string $plate, array $options = [])
 * @method static string uscc(string $uscc, array $options = [])
 * @method static string hkMoPass(string $hkMoPass, array $options = [])
 * @method static string taiwanId(string $taiwanId, array $options = [])
 * @method static string passport(string $passport, array $options = [])
 * @method static string address(string $address, array $options = [])
 */
class Mask
{
    /** @var Masker|null */
    private static $instance = null;

    public static function instance(): Masker
    {
        if (self::$instance === null) {
            self::$instance = new Masker();
        }
        return self::$instance;
    }

    public static function setInstance(Masker $masker): void
    {
        self::$instance = $masker;
    }

    /**
     * @param array<int,mixed> $arguments
     */
    public static function __callStatic(string $method, array $arguments): string
    {
        $masker = self::instance();
        if (!method_exists($masker, $method)) {
            throw new \BadMethodCallException("Method {$method} not found on Masker.");
        }

        return (string) $masker->{$method}(...$arguments);
    }

    /**
     * 批量脱敏数组
     *
     * @param array<string,mixed> $data
     * @param array<string,string> $rules e.g. ["phone" => "phone", "id" => "idCard"]
     * @return array<string,mixed>
     */
    public static function array(array $data, array $rules = []): array
    {
        return self::instance()->maskArray($data, $rules);
    }

    /**
     * 长文本智能脱敏 (auto 模式)
     *
     * 支持纯文本和 JSON 字符串:
     * - 纯文本: 自动识别身份证、手机号、邮箱、银行卡并脱敏
     * - JSON: 递归遍历所有字符串值进行识别脱敏
     *
     * @param array<string,mixed> $options ["char" => "*", "types" => []]
     * @return string 脱敏后的文本或 JSON 字符串
     */
    public static function auto(string $text, array $options = []): string
    {
        return (new Auto())->mask($text, $options);
    }

    public static function reset(): void
    {
        self::$instance = null;
    }
}
