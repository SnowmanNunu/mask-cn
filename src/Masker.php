<?php

declare(strict_types=1);

namespace MaskCn;

use MaskCn\Strategy\BankCardStrategy;
use MaskCn\Strategy\EmailStrategy;
use MaskCn\Strategy\IdCardStrategy;
use MaskCn\Strategy\NameStrategy;
use MaskCn\Strategy\PhoneStrategy;
use MaskCn\Strategy\PlateStrategy;
use MaskCn\Strategy\StrategyInterface;

class Masker
{
    /** @var array<string, StrategyInterface> */
    private $strategies = [];

    public function __construct()
    {
        $this->register('phone', new PhoneStrategy())
            ->register('idCard', new IdCardStrategy())
            ->register('bankCard', new BankCardStrategy())
            ->register('name', new NameStrategy())
            ->register('email', new EmailStrategy())
            ->register('plate', new PlateStrategy());
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

    public function phone(string $input, array $options = []): string
    {
        return $this->strategies['phone']->mask($input, $options);
    }

    public function idCard(string $input, array $options = []): string
    {
        return $this->strategies['idCard']->mask($input, $options);
    }

    public function bankCard(string $input, array $options = []): string
    {
        return $this->strategies['bankCard']->mask($input, $options);
    }

    public function name(string $input, array $options = []): string
    {
        return $this->strategies['name']->mask($input, $options);
    }

    public function email(string $input, array $options = []): string
    {
        return $this->strategies['email']->mask($input, $options);
    }

    public function plate(string $input, array $options = []): string
    {
        return $this->strategies['plate']->mask($input, $options);
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
                $result[$key] = $this->strategies[$rules[$key]]->mask($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
