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
Mask::name("欧阳娜娜");                // "欧阳*娜"     ← 复姓识别
Mask::name("诸葛孔明");                // "诸葛*明"     ← 复姓识别
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

### 自定义脱敏规则

实现 `StrategyInterface` 并注册即可使用:

```php
use MaskCn\Mask;
use MaskCn\Strategy\StrategyInterface;

class AsteriskStrategy implements StrategyInterface
{
    public function mask(string $input, array $options = []): string
    {
        $char = isset($options["char"]) ? (string) $options["char"] : "*";
        return str_repeat($char, strlen($input));
    }
}

Mask::register("secret", new AsteriskStrategy());
Mask::secret("password123");           // "***********"
Mask::secret("password123", ["char" => "#"]); // "###########"
```

注册后的策略同样支持 `Mask::array()` 批量脱敏:

```php
Mask::array(["token" => "abc123"], ["token" => "secret"]);
// ["token" => "******"]
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

// 自定义掩码字符
Mask::auto($text, ["char" => "#"]);
// "我的电话是 138####5678,身份证是 110101########8888,邮箱 f##@bar.com"

// 限制识别类型
Mask::auto($text, ["types" => ["phone", "idCard"]]);
// 只脱敏手机号和身份证,邮箱保留原样

// JSON 字符串自动脱敏
$json = '{"user":{"phone":"13812345678","idCard":"110101199003078888"},"email":"foo@bar.com"}';
Mask::auto($json);
// '{"user":{"phone":"138****5678","idCard":"110101********8888"},"email":"f**@bar.com"}'
```

Auto 内置识别类型:

| 类型 | 示例 | 说明 |
|------|------|------|
| `idCard` | `110101199003078888` | 18位身份证 |
| `phone` | `13812345678` | 大陆手机号 |
| `email` | `foo@bar.com` | 邮箱 |
| `plate` | `京A12345` | 中国车牌(含新能源) |
| `taiwanId` | `A123456789` | 台湾身份证/居住证 |
| `hkMoPass` | `H12345678` | 港澳回乡证 |
| `passport` | `E12345678` | 护照 |
| `uscc` | `91110105MA00XXXXXX` | 统一社会信用代码(18位) |
| `bankCard` | `6222021234567890123` | 银行卡(16-19位) |

### 全局配置

通过 `Config` 类可统一设置默认掩码字符,无需在每个调用处重复传入:

```php
use MaskCn\Config;
use MaskCn\Mask;

Config::set(['char' => '#']);

Mask::phone('13812345678');        // "138####5678"
Mask::idCard('110101199003078888'); // "110101########8888"
Mask::auto('电话 13812345678');     // "电话 138####5678"
```

优先级: 方法级 `options['char']` > `Config::get('char')` > 默认 `*`。

```php
Config::set(['char' => '#']);
Mask::phone('13812345678', ['char' => '*']); // "138****5678" ← options 优先
```

```php
Config::reset(); // 清空配置
```

### PSR-3 Logger 集成

自动在日志输出前脱敏敏感字段,无需改动业务代码:

```php
use MaskCn\Logger\MaskSensitiveLogger;
use Psr\Log\LoggerInterface;

$maskLogger = new MaskSensitiveLogger($originalLogger);
$maskLogger->info('用户 phone: 13812345678, 身份证: 110101199003078888');
// 实际记录: "用户 phone: 138****5678, 身份证: 110101********8888"
```

也可在任意日志框架中使用静态处理器:

```php
use MaskCn\Logger\MaskProcessor;

$message = MaskProcessor::process('用户 phone: 13812345678');
// "用户 phone: 138****5678"
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
Mask::register("secret", new AsteriskStrategy());
```

也可作为验证规则使用(可选):

```php
use MaskCn\Laravel\Rules\MaskedField;

$request->validate([
    "phone_masked" => ["string", new MaskedField("phone")],
    "plate_masked" => ["string", new MaskedField("plate")],
    "passport_masked" => ["string", new MaskedField("passport")],
]);
```

支持的验证类型: `phone`、`idCard`、`email`、`bankCard`、`name`、`plate`、`uscc`、`hkMoPass`、`taiwanId`、`passport`、`address`。

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
- [x] v0.2.x: Auto 模式支持 char 选项和 JSON 输入
- [x] v0.3.x: 自定义脱敏规则注册
- [x] v1.0.0: 全局配置(Config) + PSR-3 Logger 集成
- [x] v1.1.0: MaskedField 验证规则扩展 + Auto 模式新增证件/车牌/USCC 识别
- [x] v1.2.0: Config 支持按策略配置保留位数 + MaskedField 支持自定义掩码字符校验

## License

MIT
