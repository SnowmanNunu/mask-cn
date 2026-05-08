<?php

declare(strict_types=1);

namespace MaskCn\Laravel\Rules;

use Illuminate\Contracts\Validation\Rule;
use MaskCn\Config;

/**
 * 验证字段是否为有效的脱敏格式 (例如 138****5678)
 */
class MaskedField implements Rule
{
    /** @var string */
    private $type;

    /** @var string */
    private $char;

    public function __construct(string $type, ?string $char = null)
    {
        $this->type = $type;
        $this->char = $char ?? Config::get("char", "*");
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

        $c = preg_quote($this->char, '/');

        switch ($this->type) {
            case 'phone':
                return (bool) preg_match("/^1\d{2}{$c}{4}\d{4}$/", $value);
            case 'idCard':
                return (bool) preg_match("/^\d{6}{$c}{8}\d{4}$/", $value);
            case 'email':
                return strpos($value, $this->char) !== false && strpos($value, '@') !== false;
            case 'bankCard':
                return (bool) preg_match("/^\d{4}( {$c}{4,}|{$c}+)\d{4}$/", $value);
            case 'name':
                return mb_strlen($value) >= 2 && preg_match('/[\x{4e00}-\x{9fa5}]/u', $value) && strpos($value, $this->char) !== false;
            case 'plate':
                return (bool) preg_match("/^[\x{4e00}-\x{9fa5}][A-Z]{$c}+[A-Z0-9]{2,}$/u", $value);
            case 'uscc':
                return (bool) preg_match("/^[A-Z0-9]{3,8}{$c}+[A-Z0-9]{2,3}$/i", $value);
            case 'hkMoPass':
                return (bool) preg_match("/^[HM]{$c}+\d{4}$/i", $value) || preg_match("/^\d{6}{$c}+\d{4}[Xx\d]$/", $value);
            case 'taiwanId':
                return (bool) preg_match("/^[A-Z]\d{$c}+\d{3}$/i", $value) || preg_match("/^\d{6}{$c}+\d{4}[Xx\d]$/", $value);
            case 'passport':
                return (bool) preg_match("/^[A-Z]{$c}+\d{4,}$/i", $value);
            case 'address':
                return preg_match('/[\x{4e00}-\x{9fa5}]/u', $value) && strpos($value, $this->char) !== false;
            default:
                return strpos($value, $this->char) !== false;
        }
    }

    public function message(): string
    {
        return "The :attribute must be a valid masked {$this->type}.";
    }
}
