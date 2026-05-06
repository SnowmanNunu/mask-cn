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
            case 'bankCard':
                return (bool) preg_match('/^\d{4}( \*{4,}|\*+)\d{4}$/', $value);
            case 'name':
                return mb_strlen($value) >= 2 && preg_match('/[\x{4e00}-\x{9fa5}]/u', $value) && strpos($value, '*') !== false;
            case 'plate':
                return (bool) preg_match('/^[\x{4e00}-\x{9fa5}][A-Z]\*+[A-Z0-9]{2,}$/u', $value);
            case 'uscc':
                return (bool) preg_match('/^[A-Z0-9]{3,8}\*+[A-Z0-9]{2,3}$/i', $value);
            case 'hkMoPass':
                return (bool) preg_match('/^[HM]\*+\d{4}$/i', $value) || preg_match('/^\d{6}\*+\d{4}[Xx\d]$/', $value);
            case 'taiwanId':
                return (bool) preg_match('/^[A-Z]\d\*+\d{3}$/i', $value) || preg_match('/^\d{6}\*+\d{4}[Xx\d]$/', $value);
            case 'passport':
                return (bool) preg_match('/^[A-Z]\*+\d{4,}$/i', $value);
            case 'address':
                return preg_match('/[\x{4e00}-\x{9fa5}]/u', $value) && strpos($value, '*') !== false;
            default:
                return strpos($value, '*') !== false;
        }
    }

    public function message(): string
    {
        return "The :attribute must be a valid masked {$this->type}.";
    }
}
