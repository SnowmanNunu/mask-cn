<?php

declare(strict_types=1);

namespace MaskCn;

use MaskCn\Strategy\AddressStrategy;
use MaskCn\Strategy\BankCardStrategy;
use MaskCn\Strategy\EmailStrategy;
use MaskCn\Strategy\HkMacaoPassStrategy;
use MaskCn\Strategy\IdCardStrategy;
use MaskCn\Strategy\NameStrategy;
use MaskCn\Strategy\PassportStrategy;
use MaskCn\Strategy\PhoneStrategy;
use MaskCn\Strategy\PlateStrategy;
use MaskCn\Strategy\StrategyInterface;
use MaskCn\Strategy\TaiwanIdStrategy;
use MaskCn\Strategy\UnifiedSocialCreditCodeStrategy;

class Masker
{
    /** @var array<string, StrategyInterface> */
    private $strategies = [];

    public function __construct()
    {
        $this->register("phone", new PhoneStrategy())
            ->register("idCard", new IdCardStrategy())
            ->register("bankCard", new BankCardStrategy())
            ->register("name", new NameStrategy())
            ->register("email", new EmailStrategy())
            ->register("plate", new PlateStrategy())
            ->register("uscc", new UnifiedSocialCreditCodeStrategy())
            ->register("hkMoPass", new HkMacaoPassStrategy())
            ->register("taiwanId", new TaiwanIdStrategy())
            ->register("passport", new PassportStrategy())
            ->register("address", new AddressStrategy());
    }

    public function register(string $type, StrategyInterface $strategy): self
    {
        $this->strategies[$type] = $strategy;
        return $this;
    }

    public function get(string $type): ?StrategyInterface
    {
        return $this->strategies[$type] ?? null;
    }

    /**
     * 将 Config 中对应策略的默认配置合并到 options( options 优先级更高 )
     *
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function mergeOptions(string $type, array $options): array
    {
        $config = Config::get($type, []);
        if (is_array($config)) {
            return array_merge($config, $options);
        }
        return $options;
    }

    public function phone(string $input, array $options = []): string
    {
        return $this->strategies["phone"]->mask($input, $this->mergeOptions('phone', $options));
    }

    public function idCard(string $input, array $options = []): string
    {
        return $this->strategies["idCard"]->mask($input, $this->mergeOptions('idCard', $options));
    }

    public function bankCard(string $input, array $options = []): string
    {
        return $this->strategies["bankCard"]->mask($input, $this->mergeOptions('bankCard', $options));
    }

    public function name(string $input, array $options = []): string
    {
        return $this->strategies["name"]->mask($input, $this->mergeOptions('name', $options));
    }

    public function email(string $input, array $options = []): string
    {
        return $this->strategies["email"]->mask($input, $this->mergeOptions('email', $options));
    }

    public function plate(string $input, array $options = []): string
    {
        return $this->strategies["plate"]->mask($input, $this->mergeOptions('plate', $options));
    }

    public function uscc(string $input, array $options = []): string
    {
        return $this->strategies["uscc"]->mask($input, $this->mergeOptions('uscc', $options));
    }

    public function hkMoPass(string $input, array $options = []): string
    {
        return $this->strategies["hkMoPass"]->mask($input, $this->mergeOptions('hkMoPass', $options));
    }

    public function taiwanId(string $input, array $options = []): string
    {
        return $this->strategies["taiwanId"]->mask($input, $this->mergeOptions('taiwanId', $options));
    }

    public function passport(string $input, array $options = []): string
    {
        return $this->strategies["passport"]->mask($input, $this->mergeOptions('passport', $options));
    }

    public function address(string $input, array $options = []): string
    {
        return $this->strategies["address"]->mask($input, $this->mergeOptions('address', $options));
    }

    /**
     * 批量脱敏数组
     *
     * @param array<string, mixed> $data
     * @param array<string, string> $rules
     * @return array<string, mixed>
     */
    public function maskArray(array $data, array $rules = []): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (isset($rules[$key]) && is_string($value) && isset($this->strategies[$rules[$key]])) {
                $result[$key] = $this->strategies[$rules[$key]]->mask($value, $this->mergeOptions($rules[$key], []));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
