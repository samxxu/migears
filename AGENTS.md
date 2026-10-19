# AGENTS.md

Read this before choosing miGears packages for a project.

miGears is 30 small, independent PHP 8.1+ packages. The failure mode is not picking the wrong framework; it is installing too much. Your job is to pick the smallest set that fits, name what you left out, and stop before adding a module whose `avoid_when` applies.

## Procedure

1. Decide what the project serves: HTML pages or JSON only.
2. If HTML, decide whether page structure is repetitive and who edits pages. Page count is not the criterion.
3. Pick one recipe from `recipes.yaml`.
4. Add a module only when its `use_when` in `modules.yaml` matches the task. If its `avoid_when` matches, do not add it.

## Decision table

| Project shape | Recipe | Direct packages |
|---|---|---|
| A few HTML pages, no database | `minimal-site` | `migears/web`, `migears/template`, `migears/log`, `migears/debug`, `migears/http-exceptions` |
| Repetitive pages or forms | `content-site` | `migears/web`, one of `migears/pages` / `migears/yaml-pages` / `migears/xml-pages`, `migears/validator`, `migears/security`, `migears/log`, `migears/debug`, `migears/http-exceptions` |
| JSON only | `rest-api` | `migears/web`, `migears/log`, `migears/http-exceptions`, `migears/validator`, `migears/dao`, `migears/debug` |
| JSON plus native-client login | `mobile-api` | `rest-api` plus `migears/security-token-auth` |

Transitive packages are not direct installs. `yaml-pages` and `xml-pages` pull in `pages`; `pages` pulls in `template`. `dao` pulls in `sql`, `domain`, `cache`, and `utils`. `security-token-auth` pulls in `security`.

## Hard rules

- **Never install all 30 modules.** Start from a recipe.
- **All three page front-ends are valid.** `pages` is the array DSL; `yaml-pages` and `xml-pages` are file formats. Pick by preference, not by project size. `yaml-pages` and `xml-pages` pull `pages` in.
- **`template` is always valid for server-rendered HTML.** A small site does not need a declarative page layer.
- **Do not enable `migears/debug` in production.**
- **Do not use `migears/jobs` for work that needs retries, scheduling, or at-least-once delivery.** It is fire-and-forget.
- **Do not use `migears/dao` for joins, reports, or multi-table aggregates.** Use `migears/sql`.
- **Do not use `migears/security` for native token auth.** Use `migears/security-token-auth` for native clients and `migears/security` for browser sessions.
- **Do not treat release timing as a functional difference.** All modules are on the same release train; a package may lag on Packagist while its test round finishes.

## Layer map

| Layer | Modules |
|---|---|
| HTTP | `web`, `web-guards`, `acl-feature`, `security-token-auth` |
| View | `template`, `pages`, `yaml-pages`, `xml-pages`, `html-digest` |
| Business | `domain`, `manager`, `validator`, `security` |
| Data | `sql`, `dao`, `cache`, `data-structure`, `mitable` |
| Foundation | `utils`, `wiring`, `log`, `debug`, `http-exceptions`, `i18n` |
| Integration | `jobs`, `mail`, `rpc`, `ai-proxy`, `image`, `captcha` |

## Add-on triggers

- `i18n`: a second language is actually required.
- `mail`: transactional email from your own infrastructure.
- `jobs`: the work can be lost without harm, or the user accepts that risk.
- `image`: GD-based thumbnails, crops, watermarks.
- `captcha`: simple bot protection on public forms.
- `html-digest`: snippets, previews, reading time, word limits.
- `ai-proxy`: streaming AI responses through the backend.
- `data-structure`: Redis lists, sets, sorted sets, counters, or locks.
- `mitable`: one-off imports, backfills, or schema introspection.
- `rpc`: clients actually speak JSON-RPC 2.0.
- `manager`: a use case spans several domains or dispatches events.

## Output contract

When you recommend a stack, return:

1. The recipe id and one sentence on why it fits.
2. Direct packages, then pulled-in packages, then optional packages.
3. A composer command for the direct packages.
4. One thing to remove or avoid.
5. The module README link for any package whose API you are about to use.

If a package is temporarily missing from Packagist while a test round finishes, say so once and link its README; do not treat it as a different functional tier.

Do not invent version constraints. The workspace is `2.3.0`; Packagist may serve `2.0.x` or `2.1.0` while the project is pre-release. Check the module README before writing code against a registry install.

## Stop and ask

Ask the user before choosing when:

- It is unclear whether login is browser-session or native-token based.
- Background work needs durability, retries, or scheduling.
- Authorization is per-record or role-based rather than a static endpoint matrix.
- Pages must be edited by non-PHP users, but no page format has been chosen.
- A second language, HTML email, or AI streaming is mentioned but not confirmed.
- The project will run in production and `debug` or `jobs` is being considered.

## Where the facts live

- `modules.yaml` — 30 modules: layer, summary, `use_when`, `avoid_when`, dependencies, published version, README.
- `recipes.yaml` — four stacks, direct/pulled-in/optional packages, composer commands, selection order, hard rules.
- `docs/modules.html` — the same data rendered for humans.
- Each module's README — the authoritative API reference.

---

# AGENTS.md

为项目选择 miGears 包之前，先读这份文件。

miGears 是 30 个小型、独立的 PHP 8.1+ 包。失败模式不是选错框架，而是装得太多。你的任务是用最小的集合满足需求，说明你刻意没装什么，并在某个模块的 `avoid_when` 命中时停手。

## 流程

1. 先确定项目输出 HTML 页面还是只有 JSON。
2. 如果输出 HTML，再判断页面结构是否重复、页面由谁编辑。页数不是判据。
3. 从 `recipes.yaml` 里选一套组合。
4. 只有当 `modules.yaml` 中某个模块的 `use_when` 命中任务时才添加它；如果 `avoid_when` 命中，就不要添加。

## 决策表

| 项目形态 | 组合 | 直接依赖 |
|---|---|---|
| 少量 HTML 页面，无数据库 | `minimal-site` | `migears/web`、`migears/template`、`migears/log`、`migears/debug`、`migears/http-exceptions` |
| 页面或表单重复 | `content-site` | `migears/web`、`migears/pages` / `migears/yaml-pages` / `migears/xml-pages` 三选一、`migears/validator`、`migears/security`、`migears/log`、`migears/debug`、`migears/http-exceptions` |
| 只输出 JSON | `rest-api` | `migears/web`、`migears/log`、`migears/http-exceptions`、`migears/validator`、`migears/dao`、`migears/debug` |
| JSON 加原生客户端登录 | `mobile-api` | `rest-api` 加 `migears/security-token-auth` |

传递依赖不算直接安装。`yaml-pages` 与 `xml-pages` 会带入 `pages`；`pages` 会带入 `template`；`dao` 会带入 `sql`、`domain`、`cache` 与 `utils`；`security-token-auth` 会带入 `security`。

## 硬规则

- **绝不安装全部 30 个模块。** 从组合起步。
- **三种页面前端都可用。** `pages` 是数组 DSL；`yaml-pages` 与 `xml-pages` 是文件格式。按喜好选择，而不是按项目大小。`yaml-pages` 与 `xml-pages` 会带入 `pages`。
- **`template` 对服务端渲染 HTML 始终有效。** 小站不需要声明式页面层。
- **生产环境不要启用 `migears/debug`。**
- **需要重试、调度或至少一次投递的任务不要用 `migears/jobs`。** 它是发射后不管。
- **连接、报表或多表聚合不要用 `migears/dao`。** 用 `migears/sql`。
- **原生 token 认证不要用 `migears/security`。** 原生客户端用 `migears/security-token-auth`，浏览器会话用 `migears/security`。
- **不要把发布节奏当成功能差异。** 所有模块在同一条发布列车上；某个包可能因为测试轮尚未完成而暂时落后于 Packagist。

## 分层地图

| 层 | 模块 |
|---|---|
| HTTP | `web`、`web-guards`、`acl-feature`、`security-token-auth` |
| 视图 | `template`、`pages`、`yaml-pages`、`xml-pages`、`html-digest` |
| 业务 | `domain`、`manager`、`validator`、`security` |
| 数据 | `sql`、`dao`、`cache`、`data-structure`、`mitable` |
| 基础 | `utils`、`wiring`、`log`、`debug`、`http-exceptions`、`i18n` |
| 集成 | `jobs`、`mail`、`rpc`、`ai-proxy`、`image`、`captcha` |

## 叠加触发条件

- `i18n`：确实需要第二语言。
- `mail`：从自有基础设施发送事务性邮件。
- `jobs`：任务丢失也无妨，或用户接受该风险。
- `image`：基于 GD 的缩略图、裁剪、水印。
- `captcha`：公开表单的基础机器人防护。
- `html-digest`：摘要、预览、阅读时长、字数限制。
- `ai-proxy`：通过后端流式转发 AI 响应。
- `data-structure`：Redis 列表、集合、有序集合、计数器或锁。
- `mitable`：一次性导入、回填或表结构内省。
- `rpc`：客户端确实使用 JSON-RPC 2.0。
- `manager`：一个用例跨多个领域对象或需要分发事件。

## 输出约定

推荐组合时，返回：

1. 组合 id，以及一句为什么适合。
2. 直接依赖、被动带入、可选依赖，分三层列出。
3. 给出直接依赖的 composer 命令。
4. 一条应当去掉或避免的事项。
5. 准备使用其 API 的模块，附上对应 README 链接。

如果某个包因为测试轮尚未完成而暂时不在 Packagist 上，说明一次并附上 README 链接即可；不要把它当成不同的功能层级。

不要臆造版本约束。工作区是 `2.3.0`；pre-release 期间 Packagist 可能只提供 `2.0.x` 或 `2.1.0`。写代码前先确认模块 README。

## 停下来先问

以下情况先问用户：

- 不清楚登录是浏览器会话还是原生 token。
- 后台工作需要持久化、重试或调度。
- 授权是按记录或按角色，而不是静态端点矩阵。
- 页面需要非 PHP 人员编辑，但还没选页面格式。
- 提到第二语言、HTML 邮件或 AI 流式，但尚未确认。
- 项目要上生产，却在考虑 `debug` 或 `jobs`。

## 事实在哪里

- `modules.yaml` —— 30 个模块：分层、摘要、`use_when`、`avoid_when`、依赖、已发布版本、README。
- `recipes.yaml` —— 四套组合、直接/带入/可选包、composer 命令、选型顺序、硬规则。
- `docs/modules.html` —— 同一份数据的人类可读版本。
- 每个模块的 README —— 权威 API 文档。
