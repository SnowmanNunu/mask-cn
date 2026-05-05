<?php

declare(strict_types=1);

namespace MaskCn\Laravel;

use Illuminate\Support\ServiceProvider;
use MaskCn\Masker;

class MaskCnServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Masker::class, static function () {
            return new Masker();
        });

        $this->app->alias(Masker::class, 'mask-cn');
    }

    /**
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [Masker::class, 'mask-cn'];
    }
}
