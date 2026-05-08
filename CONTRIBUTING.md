# Contributing Guide

感谢你对 `mask-cn` 的兴趣！本文档帮助你快速参与项目开发。

## 环境要求

- PHP >= 7.3 / 8.0 / 8.1 / 8.2 / 8.3
- Composer
- ext-mbstring

## 本地安装

1. Fork 本仓库并克隆到本地：

```bash
git clone https://github.com/SnowmanNunu/mask-cn.git
cd mask-cn
```

2. 安装依赖：

```bash
composer install
```

## 代码风格

本项目使用 [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) 进行代码风格检查，提交前请运行：

```bash
composer fix
# 或
vendor/bin/php-cs-fixer fix --diff --dry-run
```

## 测试

所有功能变更必须附带单元测试，提交前确保测试全部通过：

```bash
composer test
# 或
vendor/bin/phpunit
```

## 静态分析

本项目使用 PHPStan Level 6 进行静态分析：

```bash
composer stan
# 或
vendor/bin/phpstan analyse --level=6 src tests
```

## 提交规范

本项目采用 [Conventional Commits](https://www.conventionalcommits.org/zh-hans/v1.0.0/) 规范，提交信息格式如下：

```
<type>(<scope>): <subject>

[可选的详细描述]

[可选的 Footer]
```

### Type 说明

| 类型 | 含义 |
|------|------|
| `feat` | 新功能 |
| `fix` | 修复 Bug |
| `docs` | 仅文档变更 |
| `style` | 代码风格调整（不影响代码逻辑） |
| `refactor` | 重构 |
| `perf` | 性能优化 |
| `test` | 增加或修改测试 |
| `chore` | 构建过程或辅助工具的变动 |
| `ci` | CI 配置变更 |

### 示例

```
feat: 增加护照脱敏策略
fix(auto): 修复车牌正则误匹配中文的问题
test(config): 补充 front/back 配置单元测试
```

## 分支策略

- `master`：主分支，始终保持可发布状态
- `feature/*`：功能分支，从 `master` 检出，完成后合并回 `master`
- `fix/*`：修复分支，从 `master` 检出，完成后合并回 `master`

## Pull Request 规范

- PR 标题遵循提交规范格式。
- 描述中说明改动内容和测试方式。
- 确保 CI 检查通过（PHPUnit + PHP-CS-Fixer + PHPStan）。
- 需要至少一个 Reviewer 批准后才能合并。

## 问题反馈

如果你发现 Bug 或有功能建议，请先搜索 [Issues](https://github.com/SnowmanNunu/mask-cn/issues) 确认是否已存在。如果不存在，欢迎创建新的 Issue。

## 许可证

通过向本项目提交代码，你同意将你的贡献置于 [MIT License](LICENSE) 之下。
