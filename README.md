# mask-cn

> 中文本土场景敏感数据脱敏库 - 开箱即用,无需配置

[![Tests](https://github.com/SnowmanNunu/mask-cn/actions/workflows/tests.yml/badge.svg)](https://github.com/SnowmanNunu/mask-cn/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/snowmannunu/mask-cn.svg)](https://packagist.org/packages/snowmannunu/mask-cn)
[![Total Downloads](https://img.shields.io/packagist/dt/snowmannunu/mask-cn.svg)](https://packagist.org/packages/snowmannunu/mask-cn)
[![License](https://img.shields.io/packagist/l/snowmannunu/mask-cn.svg)](LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/snowmannunu/mask-cn.svg)](composer.json)

## 为什么不用 fuko-php/masked / pachico/magoo?

通用脱敏库**不内置任何中国本土数据规则**,要让它脱敏中国身份证、复姓姓名、中国车牌,你都得自己写正则。

| 场景 | 通用脱敏库 | mask-cn |
|---|---|---|
| 中国身份证 18 位识别 + 校验 | 自己写正则 | ✅ 内置 |
| 中国手机号 1xx 格式 | 通用正则 | ✅ 内置 |
| **中文姓名复姓识别** | ❌ 不支持 | ✅ 内置 80+ 复姓字典 |
| 国内银行卡号 16/19 位 | 自己写 | ✅ 内置 |
| 中国车牌(京A·12345) | ❌ 不支持 | ✅ 内置 |
| Laravel ServiceProvider/Facade | 部分 | ✅ 一键集成 |

## 安装

```bash
composer require snowmannunu/mask-cn
```

要求:PHP 7.4+ / 8.0+ / 8.1+ / 8.2+ / 8.3+,需要 ext-mbstring。

## 用法

### 基础脱敏

```php
use MaskCn\Mask;

Mask::phone('13812345678');           // "138****5678"
Mask::idCard('110101199003078888');   // "110101********8888"
Mask::bankCard('6222021234567890123'); // "6222 **** **** 0123"
Mask::name('张三');                    // "张*"
Mask::name('欧阳娜娜');                // "欧*娜娜"     ← 复姓识别
Mask::name('诸葛孔明');                // "诸*孔明"     ← 复姓识别
Mask::email('foo@example.com');        // "f**@example.com"
Mask::plate('京A12345');              // "京A***45"
```

### 数组批量脱敏

```php
$user = [
    'name' => '张小明',
    'phone' => '13812345678',
    'id_card' => '110101199003078888',
];

Mask::array($user, [
    'name' => 'name',
    'phone' => 'phone',
    'id_card' => 'idCard',
]);
// ['name' => '张*明', 'phone' => '138****5678', 'id_card' => '110101********8888']
```

### 长文本智能识别(auto 模式)

```php
$text = "我的电话是 13812345678,身份证是 110101199003078888,邮箱 foo@bar.com";
Mask::auto($text);
// "我的电话是 138****5678,身份证是 110101********8888,邮箱 f**@bar.com"
```

### Laravel 集成

服务提供者已通过 `extra.laravel.providers` 自动注册,无需手动配置。

```php
use MaskCn\Laravel\Facades\Mask;

Mask::phone($user->phone);
```

也可作为验证规则使用(可选):

```php
$request->validate([
    'phone_masked' => ['string', new MaskedField('phone')],
]);
```

## 设计文档

详见 [docs/DESIGN.md](docs/DESIGN.md)。

## 测试

```bash
composer test
composer stan      # 静态分析
composer fix       # 代码风格
```

## 路线图

- [x] v0.x: 6 个 Strategy + Auto 智能识别 + Laravel 集成
- [ ] v1.x: 港澳台证件、护照、车架号
- [ ] v1.x: 配置化(自定义掩码字符、保留位数)
- [ ] v2.x: PSR-3 Logger 集成(自动屏蔽日志中的敏感字段)

## License

MIT
