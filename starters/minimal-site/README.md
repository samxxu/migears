# minimal-site

A runnable miGears starter: file routing, one template, a logger, a development error page, and one JSON endpoint.

## Requirements

- PHP 8.1+
- Composer
- Git (only when the sibling module repositories are not already present)

## Run

```bash
php bin/setup.php
php -S 127.0.0.1:8080 -t public public/index.php
```

Open <http://127.0.0.1:8080/> for HTML and <http://127.0.0.1:8080/health> for JSON.

`bin/setup.php` prepares `.modules/` and then runs `composer install`. Inside the miGears workspace it links the sibling module repositories, so the starter runs the same tree the modules are developed in. Anywhere else it clones the five repositories from GitHub.

The public repositories may lag until the current `2.3` test round is released; the script stops with a clear message unless the current workspace tree is present.

## What is wired

| File | Role |
|---|---|
| `public/index.php` | Bootstrap. Creates `MiRest`, registers `LoggerInterface` and `Template`, dispatches the request. |
| `resources/index.php` | Answers `GET /`. Resolves `Template` from the container and returns HTML. |
| `resources/health.php` | Answers `GET /health`. Returns JSON. |
| `views/home.php` | Native PHP template with `$this->e()` escaping. |
| `bin/setup.php` | Links or clones module sources, then installs dependencies. |

Routes are files: `resources/health.php` is `/health`. There is no route table.

## Production notes

- Remove `DebugPage::register()` from `public/index.php`.
- Keep `resources/` outside the document root. The built-in server command above does that by using `public/` as the root and `public/index.php` as the router.
- `var/log/` must be writable.

## Packages

`migears/web`, `migears/template`, `migears/log`, `migears/debug`, `migears/http-exceptions`, all at `^2.3`.

The starter uses path repositories so it can run the current pre-release tree. Once 2.3 is on Packagist the path repositories can be removed and the same command works from the registry.

---

# minimal-site

可运行的 miGears starter：文件路由、一个模板、一个日志器、一个开发错误页，以及一个 JSON 端点。

## 要求

- PHP 8.1+
- Composer
- Git（仅当同级模块仓库还不在本机时需要）

## 运行

```bash
php bin/setup.php
php -S 127.0.0.1:8080 -t public public/index.php
```

打开 <http://127.0.0.1:8080/> 查看 HTML，打开 <http://127.0.0.1:8080/health> 查看 JSON。

`bin/setup.php` 先准备 `.modules/`，再执行 `composer install`。在 miGears 工作区内，它会链接同级的模块仓库，因此 starter 跑的就是模块开发所在的同一棵树；在其他位置，它从 GitHub 克隆这五个仓库。

当前 `2.3` 测试轮发布前，公开仓库可能滞后；除非本机存在当前工作区代码树，脚本会带着明确提示停下。

## 接线方式

| 文件 | 作用 |
|---|---|
| `public/index.php` | 启动文件。创建 `MiRest`，注册 `LoggerInterface` 与 `Template`，分发请求。 |
| `resources/index.php` | 响应 `GET /`。从容器取出 `Template` 并返回 HTML。 |
| `resources/health.php` | 响应 `GET /health`。返回 JSON。 |
| `views/home.php` | 原生 PHP 模板，使用 `$this->e()` 转义。 |
| `bin/setup.php` | 链接或克隆模块源码，然后安装依赖。 |

路由就是文件：`resources/health.php` 就是 `/health`。没有路由表。

## 生产注意事项

- 从 `public/index.php` 中移除 `DebugPage::register()`。
- `resources/` 必须位于文档根之外。上面的内置服务器命令用 `public/` 作为根、`public/index.php` 作为路由脚本，正是为此。
- `var/log/` 必须可写。

## 依赖包

`migears/web`、`migears/template`、`migears/log`、`migears/debug`、`migears/http-exceptions`，均为 `^2.3`。

starter 使用 path 仓库，以便运行当前 pre-release 代码树。2.3 发布到 Packagist 后，可以移除 path 仓库，同一条命令即可从 registry 安装。
