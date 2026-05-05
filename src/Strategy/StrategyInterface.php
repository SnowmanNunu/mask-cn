<?php

declare(strict_types=1);

namespace MaskCn\Strategy;

interface StrategyInterface
{
    /**
     * @param array<string, mixed> $options 例如 ['char' => '*']
     */
    public function mask(string $input, array $options = []): string;
}
