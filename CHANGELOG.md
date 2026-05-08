# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] - 2026-05-06

### Added
- `Config` 支持按策略类型配置 `front` / `back` 保留位数，例如 `Config::set(['phone' => ['front' => 2, 'back' => 3]])`。
- 所有内置策略（`phone`、`idCard`、`bankCard`、`plate`、`name`、`email`、`uscc` 等）均支持 `front` / `back` 选项覆盖。
- `MaskedField` 验证规则支持自定义掩码字符，默认读取 `Config::get('char', '*')`。

### Changed
- `options['char']` > `Config::get('char')` > 默认值 `*` 的优先级链保持不变。

## [1.1.0] - 2026-05-05

### Added
- `MaskedField` 验证规则扩展至 11 种类型：`phone`、`idCard`、`email`、`bankCard`、`name`、`plate`、`uscc`、`hkMoPass`、`taiwanId`、`passport`、`address`。
- `Mask::auto()` 长文本智能识别新增 `plate`（车牌）、`passport`（护照）、`hkMoPass`（港澳证件）、`taiwanId`（台湾证件）、`uscc`（统一社会信用代码）五种识别器。
- `Auto` 模式支持 JSON 字符串自动脱敏。

### Fixed
- 车牌正则避免中文误匹配（将 `\w` 调整为 `[A-Za-z0-9]` 做边界）。
- 台湾身份证与护照识别顺序优化，避免 `A123456789` 被误判为护照。

## [1.0.0] - 2026-05-04

### Added
- 全局配置类 `Config`：支持 `set()`、`get()`、`reset()`，统一设置默认掩码字符。
- PSR-3 Logger 集成：`MaskSensitiveLogger` 装饰器与 `MaskProcessor` 静态处理器，日志输出前自动脱敏。
- 优先级链：`options['char']` > `Config::get('char')` > 默认 `*`。

### Changed
- 首個 Stable 版本，API 冻结。

## [0.2.0] - 2026-05-03

### Added
- `Mask::uscc()`：统一社会信用代码、组织机构代码、旧版营业执照脱敏。
- `Mask::hkMoPass()`：港澳通行证 / 港澳居住证脱敏。
- `Mask::taiwanId()`：台湾身份证 / 台湾居住证脱敏。
- `Mask::passport()`：护照脱敏。
- `Mask::address()`：中文地址脱敏（保留省/市，其余掩码）。
- `Mask::auto()` 支持 `char` 选项与 JSON 输入自动脱敏。
- 自定义脱敏规则注册：`Mask::register($name, StrategyInterface)`。

## [0.1.0] - 2026-05-02

### Added
- 基础脱敏策略：`phone`（手机号）、`idCard`（身份证）、`bankCard`（银行卡）、`name`（中文姓名，含复姓识别）、`email`（邮箱）、`plate`（车牌）。
- `Mask::array()` 数组批量脱敏。
- `Mask::auto()` 长文本智能识别（自动匹配手机号、身份证、邮箱、银行卡、车牌）。
- Laravel 集成：`ServiceProvider`、`Facade`、`MaskedField` 验证规则（初版仅 phone/idCard/email/bankCard/name/plate）。
- PHPUnit 测试覆盖与 GitHub Actions CI。

