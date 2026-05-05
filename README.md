# mask-cn

> 中文本土场景敏感数据脱敏库 - 开箱即用,无需配置

[![Tests](https://github.com/SnowmanNunu/mask-cn/actions/workflows/tests.yml/badge.svg)](https://github.com/SnowmanNunu/mask-cn/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/snowmannunu/mask-cn.svg)](https://packagist.org/packages/snowmannunu/mask-cn)
[![Total Downloads](https://img.shields.io/packagist/dt/snowmannunu/mask-cn.svg)](https://packagist.org/packages/snowmannunu/mask-cn)
[![License](https://img.shields.io/packagist/l/snowmannunu/mask-cn.svg)](LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/snowmannunu/mask-cn.svg)](composer.json)

## 安装

```bash
composer require snowmannunu/mask-cn
```

要求:PHP 7.3+ / 8.0+ / 8.1+ / 8.2+ / 8.3+,需要 ext-mbstring。

## 用法

### 基础脱敏

```php
use MaskCn\Mask;

Mask::phone("13812345678");           // "138****5678"
Mask::idCard("110101199003078888");   // "110101********8888"
Mask::bankCard("6222021234567890123"); // "6222 *********** 0123"
Mask::name("张三");                    // "张*"
Mask::name("欧阳娜娜");                // "欧*娜娜"     ← 复姓识别
Mask::name("诸葛孔明");                // "诸*孔明"     ← 复姓识别
Mask::email("foo@example.com");        // "f**@example.com"
Mask::plate("京A12345");              // "京A***45"
```

### 证件/机构代码脱敏

```php
Mask::uscc("91110105MA00XXXXXX");      // "91110105********XX"  ← 统一社会信用代码
Mask::uscc("123456789");               // "123****89"          ← 组织机构代码
Mask::uscc("110101123456789");         // "110101******789"    ← 旧版营业执照

Mask::hkMoPass("H12345678");           // "H****5678"          ← 港澳回乡证
Mask::hkMoPass("81000019900101001X");  // "810000********001X" ← 港澳居住证

Mask::taiwanId("A123456789");          // "A1****789"          ← 台湾身份证
Mask::taiwanId("83000019900101001X");  // "830000********001X" ← 台湾居住证

Mask::passport("E12345678");           // "E****5678"          ← 护照
```

### 地址脱敏

```php
Mask::address("广东省深圳市南山区科技园");         // "广东省深圳市******"
Mask::address("北京市朝阳区建国路");               // "北京市******"
Mask::address("广西壮族自治区南宁市青秀区民族大道"); // "广西壮族自治区南宁市*******"
```

### 数组批量脱敏

```php
$user = [
    "name" => "张小明",
    "phone" => "13812345678",
    "id_card" => "110101199003078888",
];

Mask::array($user, [
    "name" => "name",
    "phone" => "phone",
    "id_card" => "idCard",
]);
// ["name" => "张*明", "phone" => "138****5678", "id_card" => "110101********8888"]
```

### 长文本智能识别(auto 模式)

```php
$text = "我的电话是 13812345678,身份证是 110101199003078888,邮箱 foo@bar.com";
Mask::auto($text);
// "我的电话是 138****5678,身份证是 110101********8888,邮箱 f**@bar.com"
```

### 自定义掩码字符

所有方法均支持 `char` 选项:

```php
Mask::phone("13812345678", ["char" => "#"]);     // "138####5678"
Mask::idCard("110101199003078888", ["char" => "#"]); // "110101########8888"
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
    "phone_masked" => ["string", new MaskedField("phone")],
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

- [x] v0.1.x: 基础 6 个 Strategy + Auto 智能识别 + Laravel 集成
- [x] v0.2.x: 统一社会信用代码、组织机构代码、旧版营业执照
- [x] v0.2.x: 港澳通行证/居住证、台湾身份证/居住证、护照
- [x] v0.2.x: 中文地址脱敏
- [ ] v1.x: 配置化(自定义掩码字符、保留位数)
- [ ] v2.x: PSR-3 Logger 集成(自动屏蔽日志中的敏感字段)

## License

MIT
