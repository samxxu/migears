# miGears

> The entry point for miGears: which packages to install, how they fit together, and what to leave out.

miGears is a collection of 30 small, independent PHP 8.1+ packages. Each package solves one problem and stays readable in one sitting. This repository is **not** a package and is not published to Packagist. It is the map, the selection guide, the ready-made stacks, and the starter set.

- **30 modules** across 6 layers
- **4 stacks** for the common project shapes
- **1 runnable starter** in M1
- **Machine-readable metadata** for AI coding agents

All 30 modules are on the same release train. A few are still completing their test round and will appear on Packagist with the next release. The workspace tree is `2.3.0`; Packagist currently serves `2.0.x` (and `migears/web` `2.1.0`), so the registry may lag behind the docs while that round finishes.

## What miGears is

miGears is a PHP 8.1+ framework delivered as 30 independent Composer packages rather than one monolith. Each package solves a single problem and is small enough to read in one sitting — most stay within a few hundred lines, with no generated code and no magic you cannot see.

Where a standard exists, miGears uses it: PSR-11 for the container, PSR-16 for cache, PSR-3 for logging. Where none exists, it prefers a small explicit implementation over a heavy abstraction. `migears/web` (MiRest) is the framework's only container and the only place services are wired; every other package takes what it needs through its constructor, so it also works standalone, without `migears/web`.

The packages are grouped into six layers. Layers describe responsibility, not install order — you install packages, not layers.

| Layer | Responsibility | Modules |
|---|---|---|
| HTTP | Routing, request handling, and access control at the edge | `web`, `web-guards`, `acl-feature`, `security-token-auth` |
| View | Turning data into HTML, from native templates to declarative pages | `template`, `pages`, `yaml-pages`, `xml-pages`, `html-digest` |
| Business | Domain objects, validation, orchestration, and security primitives | `domain`, `manager`, `validator`, `security` |
| Data | SQL, persistence, caching, Redis structures, and one-off migrations | `sql`, `dao`, `cache`, `data-structure`, `mitable` |
| Foundation | Cross-cutting pieces every project eventually needs | `utils`, `wiring`, `log`, `debug`, `http-exceptions`, `i18n` |
| Integration | Edges to the outside world: mail, jobs, RPC, AI, media | `jobs`, `mail`, `rpc`, `ai-proxy`, `image`, `captcha` |

Nothing is a lifetime commitment. A project can start from a small stack and add a package later when its `use_when` matches, or drop one it has outgrown.

## Start here

- **Humans:** open [docs/index.html](docs/index.html), or the GitHub Pages site once enabled.
- **AI agents:** read [AGENTS.md](AGENTS.md), then [modules.yaml](modules.yaml) and [recipes.yaml](recipes.yaml).
- **Install a stack:** see the table below or [docs/stacks.html](docs/stacks.html).

## The one rule

Do not install all 30 modules. Start from a stack, then add a module only when its `use_when` matches the task. Every module also carries `avoid_when`; read it before keeping the module.

## Four stacks

| Stack | Direct packages | Use when |
|---|---|---|
| **Minimal site** | web, template, log, debug, http-exceptions | Server-rendered pages with plain PHP templates, no database |
| **Content site** | web + one page front-end (`pages`, `yaml-pages`, or `xml-pages`), validator, security, log, debug, http-exceptions | Repetitive pages and forms; the page format is your choice |
| **REST API** | web, log, http-exceptions, validator, dao, debug | JSON only; one table maps to one domain object |
| **Mobile API** | REST API plus security-token-auth | Native clients need bearer tokens with refresh rotation |

`yaml-pages` and `xml-pages` pull in `pages`; `pages` pulls in `template`. `dao` pulls in `sql`, `domain`, `cache`, and `utils`. Pick the page front-end by preference; the composer examples use `yaml-pages`. Install the top-level packages and Composer brings the rest.

## Run the starter

```bash
git clone https://github.com/samxxu/migears-entry.git
cd migears/starters/minimal-site
php bin/setup.php
php -S 127.0.0.1:8080 -t public public/index.php
```

`bin/setup.php` links the sibling module repositories when they are present, otherwise clones them, then runs `composer install`. Open <http://127.0.0.1:8080/> for HTML and `/health` for JSON.

## Repository layout

```text
modules.yaml          single source of truth: 30 modules, layers, use/avoid, dependencies
recipes.yaml          four stacks, selection order, hard rules
AGENTS.md             the short brief for AI coding agents
llms.txt              compact index for LLMs
docs/                 generated static site (GitHub Pages)
site/                 site templates and assets; docs/ is built from these
tools/build.php       YAML -> docs/
tools/verify.php      metadata checks, plus composer.json comparison when modules are siblings
starters/             runnable project skeletons
```

## Keeping it honest

`modules.yaml` and `recipes.yaml` are the only hand-written sources. The site is generated from them. `tools/verify.php` checks that every module, layer, recipe, dependency, and composer command is internally consistent, and compares each record with the sibling module's `composer.json` when the workspace layout is present. CI rebuilds `docs/` and fails when it is stale.

## Status

**M1** is this repository: the module map, selection guide, four stacks, one runnable starter, and the agent-facing files. M2 adds the remaining starters, a CI matrix over the stacks, and an interactive chooser. M3 adds search, a version matrix, and a “built with miGears” showcase.

## Module documentation

Each module README is authoritative. The package name is the stable reference: `migears/web`, `migears/template`, `migears/yaml-pages`, and so on. Links for all 30 are in [modules.yaml](modules.yaml).

## License

MIT.

---

# miGears

> miGears 的入口：该装哪些包、它们如何搭配，以及哪些可以不要。

miGears 是 30 个小型、独立的 PHP 8.1+ 包。每个包只解决一个问题，并且能一口气读完。本仓库**不是**一个包，也不发布到 Packagist。它是模块地图、选型指南、预制组合与 starter 集合。

- **30 个模块**，分 6 层
- **4 套组合**，覆盖常见项目形态
- **1 个可运行 starter**（M1）
- **面向 AI 编程 agent 的机器可读元数据**

目前 30 个模块都在同一条发布列车上。少数模块仍在完成测试轮，会在下一次发布时出现在 Packagist 上。工作区代码树是 `2.3.0`；Packagist 目前提供 `2.0.x`（`migears/web` 为 `2.1.0`），因此在那轮测试完成前，registry 可能落后于文档。

## miGears 是什么

miGears 是一个 PHP 8.1+ 框架，由 30 个独立的 Composer 包组成，而不是一个单体。每个包只解决一个问题，并且小到能一口气读完——多数控制在几百行，没有生成代码，也没有你看不见的魔法。

有标准就用标准：容器用 PSR-11，缓存用 PSR-16，日志用 PSR-3。没有标准可依时，它宁可写一份简短直白的实现，也不引入厚重的抽象。`migears/web`（MiRest）是框架唯一的容器，也是唯一接线服务的地方；其余包通过构造函数取用所需依赖，因此也可以脱离 `migears/web` 单独使用。

30 个包分入六层。分层描述的是职责，不是安装顺序——你安装的是包，不是层。

| 层 | 职责 | 模块 |
|---|---|---|
| HTTP | 路由、请求处理与边缘访问控制 | `web`、`web-guards`、`acl-feature`、`security-token-auth` |
| 视图 | 把数据变成 HTML，从原生模板到声明式页面 | `template`、`pages`、`yaml-pages`、`xml-pages`、`html-digest` |
| 业务 | 领域对象、校验、编排与安全原语 | `domain`、`manager`、`validator`、`security` |
| 数据 | SQL、持久化、缓存、Redis 结构与一次性迁移 | `sql`、`dao`、`cache`、`data-structure`、`mitable` |
| 基础 | 每个项目迟早会用到的横切能力 | `utils`、`wiring`、`log`、`debug`、`http-exceptions`、`i18n` |
| 集成 | 通向外部世界的边缘：邮件、任务、RPC、AI、媒体 | `jobs`、`mail`、`rpc`、`ai-proxy`、`image`、`captcha` |

没有什么是终身承诺。项目可以从一套小组合起步，之后在某个包的 `use_when` 命中时再加上它，也可以去掉已经不需要的包。

## 从这里开始

- **人：** 打开 [docs/index.html](docs/index.html)，或启用后的 GitHub Pages 站点。
- **AI agent：** 先读 [AGENTS.md](AGENTS.md)，再读 [modules.yaml](modules.yaml) 与 [recipes.yaml](recipes.yaml)。
- **安装组合：** 见下表或 [docs/stacks.html](docs/stacks.html)。

## 唯一一条规则

不要安装全部 30 个模块。从一套组合开始，只在模块的 `use_when` 命中任务时添加它。每个模块也带有 `avoid_when`；保留它之前先读一遍。

## 四套组合

| 组合 | 直接依赖 | 适用 |
|---|---|---|
| **小站** | web、template、log、debug、http-exceptions | 用纯 PHP 模板做服务端渲染页面，没有数据库 |
| **内容站** | web + 一个页面前端（`pages`、`yaml-pages` 或 `xml-pages`）、validator、security、log、debug、http-exceptions | 页面与表单重复；页面格式按喜好自选 |
| **REST API** | web、log、http-exceptions、validator、dao、debug | 只输出 JSON；一张表对应一个领域对象 |
| **移动端 API** | REST API 加 security-token-auth | 原生客户端需要 Bearer token 与刷新轮换 |

`yaml-pages` 与 `xml-pages` 会带入 `pages`；`pages` 会带入 `template`。`dao` 会带入 `sql`、`domain`、`cache` 与 `utils`。页面前端按喜好选择；composer 示例以 `yaml-pages` 为例。安装顶层包即可，Composer 会带入其余部分。

## 运行 starter

```bash
git clone https://github.com/samxxu/migears-entry.git
cd migears/starters/minimal-site
php bin/setup.php
php -S 127.0.0.1:8080 -t public public/index.php
```

`bin/setup.php` 在同级模块仓库存在时链接它们，否则克隆它们，然后执行 `composer install`。打开 <http://127.0.0.1:8080/> 查看 HTML，打开 `/health` 查看 JSON。

## 仓库结构

```text
modules.yaml          唯一事实源：30 个模块、分层、适用/不适用、依赖
recipes.yaml          四套组合、选型顺序、硬规则
AGENTS.md             面向 AI 编程 agent 的简短交底
llms.txt              面向 LLM 的紧凑索引
docs/                 生成的静态站点（GitHub Pages）
site/                 站点模板与资源；docs/ 由它们生成
tools/build.php       YAML -> docs/
tools/verify.php      元数据校验；模块在同级时对照 composer.json
starters/             可运行项目骨架
```

## 如何保持诚实

`modules.yaml` 与 `recipes.yaml` 是唯一的手写来源，站点由它们生成。`tools/verify.php` 检查每个模块、层、组合、依赖与 composer 命令的内部一致性；在工作区布局下，还会与同级模块的 `composer.json` 对照。CI 会重新生成 `docs/`，过期即失败。

## 状态

**M1** 就是本仓库：模块地图、选型指南、四套组合、一个可运行 starter，以及面向 agent 的文件。M2 增加其余 starter、覆盖各组合的 CI 矩阵与交互式选型。M3 增加搜索、版本矩阵与“用 miGears 构建”展示。

## 模块文档

每个模块的 README 是权威来源。包名是稳定引用：`migears/web`、`migears/template`、`migears/yaml-pages`，依此类推。30 个模块的链接都在 [modules.yaml](modules.yaml) 中。

## 许可证

MIT。
