<?php

declare(strict_types=1);

namespace MaskCn\Laravel\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * 验证字段是否为有效的脱敏格式 (例如 138****5678)
 */
class MaskedField implements Rule
{
    /** @var string */
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param  string  $attribute
     * @param  mixed   $value
     */
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        switch ($this->type) {
            case 'phone':
                return (bool) preg_match('/^1\d{2}\*{4}\d{4}$/', $value);
            case 'idCard':
                return (bool) preg_match('/^\d{6}\*{8}\d{4}$/', $value);
            case 'email':
                return strpos($value, '*') !== false && strpos($value, '@') !== false;
            default:
                return strpos($value, '*') !== false;
        }
    }

    public function message(): string
    {
        return "The :attribute must be a valid masked {$this->type}.";
    }
}
