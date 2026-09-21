# 最小 WordPress Starter Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans for the recommended inline execution, or superpowers:subagent-driven-development only if the user selects delegation. Steps use checkbox (`- [x]`) syntax for tracking.

**Goal:** 交付一个可以复制到独立目录启动、编辑、备份和恢复的原生 WordPress 候选起点，并验证三个 WordPress 方法的发现与使用。

**Architecture:** 在 `starters/wordpress/` 创建候选包，不修改 TiO₂ 运行文件或数据库。使用独立 Compose 项目和卷、最小 PHP Theme、Core Page/Post 及三个项目级 Skill；本阶段没有 Product、RFQ 或客户资料。

**Tech Stack:** Docker Compose、WordPress/PHP、MariaDB、WP-CLI、PowerShell 启动指导、Python 标准库检查；镜像初始候选参考当前根 compose.yaml，执行时核对可用性、安全更新与兼容性后锁定并记录实测版本。

**Spec:** [已确认的建站模板设计](../specs/2026-09-21-wordpress-website-template-design.md)，尤其 §§6–11。

## Global Constraints

- “提取从新目录进行，TiO₂ 原站保留参考基线，不先重命名插件或批量改字段。”
- “第一版没有远程运行依赖、自动升级器、通用页面引擎或额外 Framework Loader。”
- “本地配置来自 `.env.example`，真实凭据由新项目生成并排除 Git；代码、数据库和 uploads 分开备份。”
- “默认不被搜索引擎索引，开发环境禁止外发邮件。”
- “代码回退不等于数据库回退。”
- “Agent 定义不授予自动委派权限，仍服从实际任务指令。”
- Windows 为本阶段实测环境；其他平台未测时不宣称兼容。只操作本阶段命名的测试环境，数据库写入串行。

## Review Focus

- 两份复制品使用相同 Compose 标识可能共享数据：任务 1/6 验证项目名重复检测与独立卷。
- 页面能访问但不能用 Core 编辑器正常修改：任务 2/6 验证编辑保存、修订和公开呈现。
- 备份存在但遗漏 uploads 或恢复进原站：任务 3/6 在第三个新目标实际恢复媒体与正文。
- 新站依赖旧绝对路径、品牌或产品插件：任务 2/6 用无 Product 模块的干净副本启动，检查运行输出和依赖。
- Skill 文件存在但未被发现，或每次改字都重做研究：任务 5 验证新会话发现、显式调用与负例任务；无法观察则标未验证。

## 本计划覆盖与后续分段

本计划只实施设计 §11 的“最小技术候选”，附最小资料骨架及对应恢复验证。完成后可称可启动的工程候选，不能称完整新站模板已验证。

| 后续工作 | 启动输入 | 独立交付与验收 |
|---|---|---|
| 新站准备能力 | 本候选可启动；明确标虚构的企业试验资料 | 事实/研究/定位/架构能力与一批 READY 规格；检验已有资料复用与新事实缺口 |
| 业务模块与首条转化路径 | 已确定 Product 或服务模型、字段和本地接收行为 | 真实页面→后台记录，独立内容/运行审查；不包含邮件 |
| 候选发行与真实第二站 | 前两段通过、真实企业资料/用途明确 | 去绑定复验、模块清单和限制、下一站适配结果；之后才考虑稳定发行 |

后续工作各自形成短的具体实施计划，不在此预先猜产品字段或表单模型，不把它们从总目标中删掉。模板库、全局安装、远端改名、发布与邮件不在本阶段。

## 文件与接口

以下路径相对本仓库；`P = starters/wordpress/` 仅为文档缩写。复制后 P 的内容就是新项目根。

| 文件 | 责任 |
|---|---|
| `P/compose.yaml`、`P/.env.example`、`P/.gitignore` | 独立运行环境与机密/备份排除 |
| `P/scripts/preflight.py` | 启动前校验配置、端口、项目标识及已有容器；不自动改 Docker 资源 |
| `P/scripts/install.php` | 经 preflight 后以 WP-CLI 初始化的非凭据站点设置；不批量导入页面 |
| `P/wp-content/themes/site-starter/{style.css,functions.php,index.php,header.php,footer.php,page.php,single.php}` | 最小 Core 内容呈现与主题支持 |
| `P/wp-content/themes/site-starter/assets/site.css` | 内容与菜单的最小响应式样式，无品牌资产和业务样式 |
| `P/wp-content/mu-plugins/local-safety.php` | 仅本地配置启用时禁邮件、禁索引；无客户领域逻辑 |
| `P/tests/{preflight_test.py,core-content.php,http-smoke.py}` | 隔离配置、Core 编辑/修订夹具、真实 HTTP 行为检查 |
| `P/README.md`、`P/docs/{WORKFLOW.md,RELEASE.md,RECOVERY.md}` | 可执行启动、生命周期、发布边界与备份恢复步骤 |
| `P/AGENTS.md`、`P/planning/{SITE_BRIEF.md,SITE_MAP.csv,SEO_MAP.csv,CONTENT_MODEL.md,DESIGN_SYSTEM.md}` | 可填写的新站骨架，无预设客户和页面状态 |
| `P/.agents/skills/{wordpress-content-modeling,wordpress-direct-build,wordpress-runtime-verification}/SKILL.md` | 三个 DRAFT 方法；必要 references 同目录，禁止强依赖原站 |
| `docs/decisions/STARTER_FOUNDATION_REVIEW.md` | 本阶段结果、实际版本、已验证/未测和恢复记录；一份结果，不另造 Manifest |

preflight CLI：`python scripts/preflight.py --project NAME --port PORT [--resume]`。无副作用检查，成功 exit 0，输入/冲突/依赖问题 exit 非零并输出具体原因。`--resume` 仅允许 Compose labels 显示工作目录及配置文件均属于当前副本的已有项目；不能把同名项目当成本项目。

统一环境字段：`COMPOSE_PROJECT_NAME`、`WP_PORT`、`DB_PASSWORD`、`DB_ROOT_PASSWORD`、`WP_ADMIN_USER`、`WP_ADMIN_PASSWORD`、`WP_ADMIN_EMAIL`、`WP_SITE_TITLE`。`SITE_STARTER_LOCAL=1` 由本地 Compose 显式传入并映射到 WP 常量。无生产配置自动生成或解除。

## Task 1：隔离且可重复启动的环境

**Files:** 创建 compose、env example、gitignore、preflight 及其测试。只读参考根 `compose.yaml` 和 `.gitignore`。

**Interfaces:** 消费上述环境字段；产生 db/wordpress/cli 三个服务，wordpress 仅绑定 `127.0.0.1:${WP_PORT}`；卷 `database` 和 `wordpress` 使用 Compose 项目级命名，不设置跨项目固定 volume name。CLI 挂载 scripts/tests 为只读。

- [x] 在独立 Git 工作区执行；先读取适用规则并记录 TiO₂ 当前容器和卷身份，不改变它们。保存位置不能与任何现有 worktree 重叠。
- [x] 为配置纯函数 `validate_settings(project: str, port: int, protected_projects: tuple[str, ...] = ()) -> list[str]` 写实质失败用例，再实现校验。示例：

```python
def test_reject_reserved_or_invalid_settings(self):
    self.assertTrue(validate_settings('protected-site', 18081, ('protected-site',)))
    self.assertTrue(validate_settings('../other', 18081))
    self.assertTrue(validate_settings('starter-probe-a', 0))
    self.assertEqual(validate_settings('starter-probe-a', 18081), [])
```

保护列表从执行环境传入；本轮测试环境保护 `tio2-wordpress`，发行包不硬编码该值。CLI 同时支持重复的 `--protect-project NAME` 参数，传给同一校验函数。

- [x] 实现 CLI 对 Docker/Compose 可用性、localhost 端口占用和项目 labels 的只读检查。首次启动同名已有项目失败；恢复模式校验规范化目录/配置路径；符号链接/路径解析不明确时报告而非猜测。
- [x] Compose 的核心接口如下；补齐 db 健康检查、CLI 同数据库设置、主题与 mu-plugin 挂载，密码用必填变量而非默认值：

```yaml
name: ${COMPOSE_PROJECT_NAME:?Set a unique project name}
services:
  wordpress:
    ports:
      - "127.0.0.1:${WP_PORT:?Set WP_PORT}:80"
    environment:
      SITE_STARTER_LOCAL: "1"
volumes:
  database:
  wordpress:
```

- [x] `.env.example` 用空凭据，不设置真实默认密码；`.gitignore` 排除 `.env`、`.local/`、`backups/`、运行日志与上传副本。初始化说明使用安全交互或环境变量，不将密码写入报告或命令输出。
- [x] 运行 `python -m unittest discover -s tests -p preflight_test.py`；实际用两个不同项目名/端口创建环境，检查 Compose labels/卷分别归属；碰撞测试保持无写入。校验失败不得继续启动。
- [x] 提交此任务：`feat: add isolated WordPress starter environment`。

## Task 2：可编辑的最小 Theme 与本地保护

**Files:** 创建主题文件、local-safety、install.php、core-content.php 和 http-smoke.py。

**Interfaces:** Theme slug 为 `site-starter`；只用 Core Page/Post/菜单/媒体，不调用领域插件。HTTP 测试接受 `--base-url`，无 localhost:8080 固定值。夹具使用唯一前缀，在 finally 恢复/删除自身记录，不操作原站记录。

- [x] 创建本地 PHP 夹具：检查 `SITE_STARTER_LOCAL === true` 且 home host 为 localhost/127.0.0.1 后才可写；否则立即失败。初始化前后记录站点 URL，禁止仅凭报告参数判断本地。
- [x] HTTP 用例检查一个 Core Page 的实际 H1/正文、唯一 title、未知路径404、noindex、无 Titanium Dioxide/旧域名/产品导航；先在未安装主题状态看到明确失败。
- [x] 实现 Theme，正文用 Loop 与 `the_content()`，菜单用 Core 注册和渲染，提供 title-tag、post-thumbnails、HTML5、editor-styles 等实际需要的支持。核心模板片段：

```php
<?php get_header(); ?>
<main id="main-content">
<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  </article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
```

- [x] 页眉包含 skip link，导航不依赖 Product 插件，页脚只输出当前站点名称。CSS 不隐藏长文/图片溢出，用内容驱动高度、清晰焦点和可换行链接；不复制本站 Logo/字体/图片。
- [x] local-safety 仅在 WP 常量 `SITE_STARTER_LOCAL` 为真时拦截邮件并返回 false（不伪称发送成功），强制 noindex 并禁 sitemap；通过 Compose 的 config-extra 定义常量。安装设置 blog_public=0，注明这些保护不是访问控制，预览只绑定本机。
- [x] `core-content.php` 用原生 `wp_insert_post`/`wp_update_post`/`wp_restore_post_revision` 验证 Page 修改及恢复；读取最终内容而非只验证 revision 数量。发送夹具邮件应返回 false，无外部 SMTP 配置。记录并还原临时选项。
- [x] 用浏览器在后台创建或编辑段落、标题、图片和链接，保存后检查前台与手机布局；这一步补足 API 夹具无法证明的编辑体验。无浏览器或登入条件时标未验证，不用 PHP 测试代替。
- [x] 运行 PHP lint、Core 夹具及 `python tests/http-smoke.py --base-url http://127.0.0.1:18081 --page-path /sample-page/ --expected-h1 "Sample Page" --expected-text "This is an example page."`（端口先确认空闲）；截图只保留必要问题/结果证据。提交 `feat: add editable native starter theme and local safeguards`。

## Task 3：实际可恢复的数据与媒体

**Files:** 创建 `docs/RECOVERY.md`，更新 README；本阶段用可读命令，不建设通用备份平台。

**Interfaces:** 每个备份目录仅属于一个副本，包含 SQL、uploads archive、对应代码 commit 和源 URL 文本；不得提交 Git。恢复目标必须是新的 Compose 项目名和卷，仍开启本地安全保护。

- [x] 在源副本创建可识别页面正文和一张无版权顾虑的本地测试图片，记录媒体相对路径和内容哈希。
- [x] 文档提供容器内 DB export、uploads 归档、`docker compose cp` 复制的完整步骤；避免 PowerShell 文本重定向损坏二进制/SQL 编码。不打印凭据；备份命令失败时不把空文件记作成功。
- [x] 在第三个干净副本安装同一代码版本；先确认项目 labels、实际卷名和 URL 均不属于源站或 TiO₂，再导入 SQL 和 uploads。SQL 引用旧 URL 时用 WP-CLI search-replace 的 dry-run 核对后执行，并 `--skip-columns=guid`；不直接对序列化数据做字符串替换。
- [x] 验证正文、编辑状态、媒体 HTTP 响应和图片哈希；后台再修改一次并刷新前台。恢复测试只通过后才能在 README 宣称恢复经过验证。
- [x] README 明确 Git push 不能保存数据库/媒体，代码回退涉及 schema 变化时须配套数据恢复。所有清理命令先核对目标；不执行原站的 `down -v`。
- [x] 将实际命令、环境与结果写入唯一复核记录；提交 `docs: add verified isolated backup and restore procedure`。

## Task 4：最小新站资料骨架

**Files:** 创建 P/AGENTS.md、planning 五个根文件、docs/WORKFLOW.md/RELEASE.md；更新 README。

**Interfaces:** SITE_MAP 列 `page_id,url,page_type,spec`；SEO_MAP 列 `page_id,primary_keyword,title,meta_description`。表格初始只有表头，不造固定页面集。Page Spec 承担当前状态，内容依据与操作约定；数据量不由 59 页或 14 产品推导。

- [x] 写清新站填入企业事实/来源、目标、已决定和待判断项；没有资料的项明确未确认，不写虚构客户事实。模板中的填写提示是字段说明，不能作为批准内容进入网站。
- [x] CONTENT_MODEL 说明如何选择 Core 对象并记录本项目选择；不预定义 application/process Taxonomy。DESIGN_SYSTEM 保留品牌/组件/响应与检查条件的字段，不拷贝 Navy、RFQ 或 M-350。
- [x] README 内给一份最小 Page Spec 示例（不额外建重复模板）：Page ID、Status、Purpose、地图/SEO行引用、Content source、Facts、Actions and outcomes、Visual reference、Acceptance、Open decisions。正式页面只维护一份。
- [x] WORKFLOW 沿用 PLAN→READY→BUILD→REVIEW→RELEASE；RELEASE 写明实际域名、隐私、接收流程、备份与用户授权必须满足，不能从本地测试通过推出可上线。
- [x] 自检新任务能定位输入、未知和停止点；检查所有 Markdown 本地链接及表头，确认无重复状态表。文档变更无需制造镜像实现的单元测试。
- [x] 提交 `docs: add business-neutral planning and workflow skeleton`。

## Task 5：三个 WordPress Skill 的候选与发现试验

**Files:** 创建上述三个 SKILL.md，必要 references 按其方法就近保存；结果写唯一复核记录。

**Interfaces:** 每个 Skill 有唯一 name/description 和 DRAFT 状态说明；消费新项目规则/CONTENT_MODEL/Page Spec，输出当前任务结果，不创建额外 Manifest。不设自定义 Agent，不全局安装。

- [x] 执行前读取适用 skill-creator / writing-skills 指南，遵守用户选定的执行方式；技能使用不自动授权子 Agent 或外发。
- [x] 写建模方法：Page/Post/CPT/Taxonomy/Meta/Block/Template 选择依据；关系事实、展示、Discovery 分离只在有这些对象时应用；身份/来源及修订风险。
- [x] 写直接开发方法：先查现有实现，再明确最小改动，Core 优先；Theme/Plugin 分工、受控导入保留编辑；不开第二套原型站；业务参数来自规格。
- [x] 写运行验收方法：WP数据→PHP→HTTP→浏览器；证据类型、实际运行身份、未测项、适用表单结果与修订/导入/依赖验证；普通文案改动不默认运行所有写库测试。
- [x] 每项使用下列 YAML 形式；description 写触发条件，不写“每次任务必须运行”：

```yaml
---
name: wordpress-content-modeling
description: Use when deciding or changing a native WordPress content model for repeated records, relationships, editor fields or revisions.
---
```

- [x] 在复制后新项目根启动新 Codex 会话检查三项实际可发现；不能用手动读文件成功代替。客户端不支持时记录具体缺口并停止该项能力验收，环境与 Theme 的成果可保留。
- [x] 正例：设备供应商参数记录；服务企业无需型号；已有产品修订缺字段。核对方法能给出不同合理模型且不带 TiO₂ 数值。
- [x] 负例：已批准页面仅修改一个链接。不得触发全站研究、创建 Product CPT、重做品牌或强制全部 Skill。显式调用成功与隐式选择观察分别记录，不宣称确定性自动触发。
- [x] 直接开发/验收以任务2测试页的获授权可逆编辑试验，保留实际输出和必要前后差异；没有真正使用过的 Skill 保持 DRAFT。提交 `feat: add draft WordPress methods with scoped usage checks`。

## Task 6：干净副本验收和交付

**Files:** 完成 `docs/decisions/STARTER_FOUNDATION_REVIEW.md`；更新候选 README 的实际支持范围。

**Interfaces:** 消费前五任务的精确提交、实际环境与观察。产生一份可审结论，不自动发版、合并 main 或推送。

- [x] 只复制候选包的 Git 跟踪文件到一个新路径；不带 .env、缓存、卷、备份和原站源码。为副本初始化独立 Git，按 README 从头启动，不能用旧环境测试结果替代。
- [x] 检查文件与输出中的旧项目路径、域名、型号、品牌资产和业务 key；扫描命中需要人工区分示例/引用与运行依赖。P 内不依赖 D23/D33 或 root planning；扫描只是辅助，不替代实际启动。
- [x] 复核两个副本的卷独立、同名/端口冲突拒绝、第三副本恢复结果；确认原 TiO₂ 容器/卷身份未变，未执行业务数据写入。
- [x] 对最终候选执行相关配置/Core/HTTP 检查和浏览器任务；版本没变且范围有效的备份/Skill证据可引用，受影响变化重验。不测试不存在的 Product/RFQ 后宣称它们通过。
- [x] 按用户选择的执行方式安排最终独立代码审查；审查者读取设计、本计划、实际差异及证据，重点看保护失效和隐藏业务绑定。只自检时明确没有独立审查，不能自行换名字宣称独立通过。
- [x] 报告记录已完成、实际命令/版本、问题与修复、未测和后续分段。结论只能为“最小技术候选通过/需修复/部分未验证”，不能写成整套新站模板成熟。
- [x] 提交 `test: verify clean starter copy and document foundation limits`，向用户交付实际分支/提交与结果；保留原有未跟踪文件，不将本地秘密或测试备份提交。

## 计划自检与执行选择

本阶段任务覆盖设计 §6 的最小核心、§7 的必要去绑定、§8 的编辑与资料边界、§9 的候选复制、§10 对应基础验证及 §11 第二段。完整新站准备、业务模块、首条转化和真实第二站明确由后续分段承接，不在当前计划中伪造详细字段。

建议由当前主任务串行执行，最终做一次独立审查。环境、文件路径、数据库夹具和恢复有强依赖，逐任务多人并行收益低。若用户选择子 Agent 方式，则逐任务交付及审查，数据库操作仍串行。执行方式和本计划需用户确认后再开始代码提取。

实施复核：本阶段已完成，见 docs/decisions/STARTER_FOUNDATION_REVIEW.md。设计 §8 的页面状态词为 PLANNED / READY / BUILDING / REVIEW / ACCEPTED / RELEASED；它与本计划的动作名称 PLAN / READY / BUILD / REVIEW / RELEASE 分开。独立审查发现的 HTTP 页面正文覆盖已补齐，最终包含 9 项 Python 测试。
