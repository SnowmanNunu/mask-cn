<?php

declare(strict_types=1);

namespace MaskCn\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use MaskCn\Masker;

/**
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
 * @method static array  maskArray(array $data, array $rules = [])
 *
 * @see \MaskCn\Masker
 */
class Mask extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Masker::class;
    }
}
