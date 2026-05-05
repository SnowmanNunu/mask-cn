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
     * @param string[] $types 限制识别类型,空表示识别所有
     */
    public static function auto(string $text, array $types = []): string
    {
        return (new Auto())->mask($text, $types);
    }

    public static function reset(): void
    {
        self::$instance = null;
    }
}
