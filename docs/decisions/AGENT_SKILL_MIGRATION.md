# Agent / Skill 能力迁移决策表

日期：2026-09-21。状态：**迁移建议，待用户确认；不是运行配置，也不是能力验收结果。**

后续目标已扩大为下一个网站的完整建站起点，参见[可复用 WordPress 建站模板设计](../superpowers/specs/2026-09-21-wordpress-website-template-design.md)。本表继续作为旧能力来源盘点；第 7 节的优先次序仅针对当前网站增量，不是新站模板的完整范围。

本轮只盘点和提出去向，不创建 `.codex/agents`、`.agents/skills`，不修改全局能力配置、WordPress 或原策划仓库。下文目标名称是候选，不表示已安装或可调用。

## 1. 实际范围与判断

来源：`D:/23MySec`，`longestGj/tio2mydesign@765c66ed2b2d9d42cacab9009c7b17830cfdedf5`。目标基线：`wordpress-test@f624280`。

按直属 `agents/*/agent.md`、`skills/*/SKILL.md` 盘点：**18 个角色源、10 个方法源**，而非讨论材料中的 15＋9。README、references、scripts、tests 和 history 不重复计数。以下逐项依据角色/方法正文中的职责、操作边界、结果与状态作判断；引用文档不递归等同为全部已核验，支持资产另列处置范围。

需要修正的三个前提：

- W1–W5 角色源明确写有 `NOT_RUNTIME_REGISTERED / NOT_FIELD_TESTED`；可提取的方法价值不等于已有运行验证。
- 旧 Gate 3 已于源文件中标为历史范围，`gate4-complete-visual` 已吸收结构及旧视觉阶段职责；不能再把这些阶段当成多个新岗位。
- 旧运行核验已有静态单站 V1.1 与旧 V1.0 兼容区分，不能笼统说它仍一律要求 CMS/API 或 Build ID。但两条旧证据协议都不是本项目原生 WordPress 的必经流程。

迁移原则：保留降低实际错误风险的方法；按职责拆分而非整文件复制；当前站点事实继续归 `planning/`；原文件、历史决定及旧脚本留原仓库。ARCHIVE 在本表仅表示退出新日常运行，不执行远端归档或删除。

## 2. 原生入口与运行验证边界

已核对 2026-09-21 官方文档：

- 项目 Skill 使用 `.agents/skills/<name>/SKILL.md`；Codex 从当前目录到仓库根查找相应目录。元数据用于发现，完整指令按调用读取。[Build skills](https://learn.chatgpt.com/docs/build-skills)
- 项目自定义子 Agent 使用 `.codex/agents/*.toml`；必要字段为 `name`、`description`、`developer_instructions`。[Subagents](https://learn.chatgpt.com/docs/agent-configuration/subagents)
- `AGENTS.md` 按项目根至当前目录组合，支持局部覆盖；全局规则也参与指令链。[AGENTS.md](https://learn.chatgpt.com/docs/agent-configuration/agents-md)

这些是文档支持的入口，**本轮尚未验证此桌面会话加载新增配置**。将来迁入时须在当前客户端新任务中验证发现、触发、权限和结果。不得自造 Framework Loader，不把普通 `agent.md` 当已注册子 Agent，不硬编码模型或推理强度。个人全局安装另行决定。

下表路径缩写：`A:name` = `.codex/agents/name.toml`；`S:name` = `.agents/skills/name/SKILL.md`。`一期`表示建议优先试验，仍需本表确认；`按需`表示遇到相应新任务再迁，不为目录完整提前制作。

## 3. 18 个 Agent 逐项迁移

每项源文件均为 `agents/<旧名称>/agent.md`，完整可点击来源见文末。移除项不取消当前用户授权、事实边界或真实审查独立性。

| 旧名称 | 处置与理由：保留什么 | 去掉或重写什么 | 候选去向 / 时机 | 最小验证任务 |
|---|---|---|---|---|
| enterprise-product-facts | KEEP AS AGENT，拆出方法。六类企业资料、型号/单位/条件、事实可信与公开用途分离；有明确独立研究结果 | W1、总控交接字段、准备合同；本站事实和固定路径退出角色 | `A:business-product-facts`；必要时提取 `S:business-product-fact-extraction`，按需 | 用一份真实 TDS 加冲突陈述，准确保留单位、典型值、版本和适用产品；没有依据的应用不得补写 |
| market-customer-competition | KEEP AS AGENT。客户采购任务、商业竞争与搜索竞争分离，FACT/自述/推断/假设分类 | W2 接收合同、固定审批接力；不把搜索 Skill 当全套市场研究 | `A:market-customer-research`，新市场问题或新站按需 | 给定一个明确市场问题，区分竞品自述与事实，不把缺搜索量写成零，不强造差异化 |
| business-strategy-positioning | KEEP AS AGENT。真实能力＋市场依据→有取舍的定位建议；既有决定可继承 | W3/W4 接力及重复确认；企业最终选择不能被研究结论替代 | `A:website-strategy`，新站或明确定位变化按需 | 在已有方向和约束下只分析实际差额，不编利润、流量收益或强造多方案 |
| site-architecture-buyer-journey | KEEP AS AGENT。页面职责、关键词归属、导航、主要及失败路径、共享能力责任 | W4/Gate 分工表、双重登记；不按一个关键词自动新增页 | `A:site-architecture`；结果更新现有 SITE_MAP/SEO_MAP/页面规格，按需 | 处理一个与现有页重叠的页面提案，判断合并或独立，并走通未知型号到正确接收页的路径 |
| production-preparation | CONVERT TO SKILL，暂不建专职 Agent。保留代表页、复用风险、依赖和增量批次安排 | W5 准入包、额外派发岗位、独立状态；不重做已完成页面 | 先由 `docs/WORKFLOW.md` 和主任务采用；复杂批次重复出现后候选 `S:website-build-planning`，按需 | 以剩余页面安排一批代表任务，依赖决定顺序，邮件后置，不预制全部远期任务包 |
| project-orchestrator | MERGE INTO AGENTS.md / WORKFLOW。保留恢复状态、明确范围、避免重复任务、实际读回和定向复验 | Gate 路由、关闭权限、Manifest、跨仓库派发和另设上级；原文也明确它是根任务职责 | 当前主任务＋已有规则；暂不建 `website-orchestrator.toml` | 从一个页面规格恢复工作，只做获授权差额；局部缺口不冻结无关工作，也不重复索取已给授权 |
| gate1-execution | CONVERT TO SKILL / MOVE TO PLANNING。保留已有输入复用、定向补缺、必要完整研究三种深度判断 | Gate 1 启动/关闭、固定交付组合及强制支持报告 | `S:search-intent-evidence-analysis` 与 `S:buyer-task-content-design`；结论进页面规格，按需 | 已批准产品页不搜索重做；新增实时市场命题只补对应证据，注明日期和范围 |
| gate2-execution | CONVERT TO SKILL。保留完整买家内容、最危险行动句、信息不完整路径、第三方来源语义核对 | 固定 Skeleton/全文两次确认、A/B/C 文件组合和 Manifest 升版；不继承全文唯一位置与 WP 日常编辑冲突的规则 | `S:buyer-task-content-design`、`S:page-contract-consistency-review`；试验期按需 | Documents 文案中严格区分申请与下载；行动必须有接收位置，内部核验规则不出现在正文 |
| gate2-review | KEEP AS AGENT，重写为独立冷读。先看买家实际内容，再对照来源；全范围发现后集中报告 | Gate 2 判定名、逐轮版本报告、再加同范围总控语言审查 | `A:content-reviewer`＋一致性 Skill，一期 | 对一页独立冷读，能识别来源谓词扩大、虚假 CTA 预期和遗漏限定；不得直接修改被审稿 |
| gate3-execution | ARCHIVE 角色；CONVERT TO SKILL 保留结构关系、长表/字段及跨页复用能力 | 历史独立线框关、冻结组合、策划项目禁止实现的限制 | `S:responsive-structure-design`，有复杂结构时按需 | 在真实 WP 产品长表上验证 390 宽度值、单位、脚注对应；不先制作第二套全站 HTML |
| gate4-execution | ARCHIVE 重复角色；保留品牌方向和真实长文样例方法 | 独立视觉方向阶段、向旧 Gate 5 交付、每页重选风格 | `S:brand-applied-visual-design`，新视觉问题按需 | 在 Staging 中复用既有品牌规则；真实最长字段、错误态和图片背景不丢语义 |
| gate4-complete-visual | CONVERT TO SKILL。保留跨页一致性、完整页、状态覆盖和一次自检后按差异补验 | 4A/4B、workset/bundle/freeze/关闭体系、独立规划源和禁止生产实现 | `S:wordpress-direct-build` 调用结构/品牌/整页方法，核心一期 | 用已有规格直接实现 WP 页面；共享 Header/Footer 不分叉；有完整页和适用状态证据 |
| gate5-execution | ARCHIVE 重复角色；保留整页阅读节奏与全部实例应用 | 原文为设计候选，职责与 complete-visual 重复；不新增完整视觉岗位 | `S:full-page-visual-composition`，按需 | 页面从 Header 到 Footer 各模块连续成立，不用一张 Hero 截图证明整页合格 |
| gate5-independent-visual-review | MERGE 独立审查职责。保留作者/审查者分离、首审与定向复查、真实状态操作 | Gate 5 接收合同、冻结索引、独立 PNG 生产线和重复审查轮次 | `A:runtime-reviewer` 的视觉范围＋`S:layout-interaction-verification`，一期 | 不同于实现作者的审查实例检查 1440/768/390、焦点、裁切与真实点击区；未测不写通过 |
| gate6-review-delivery | CONVERT TO SKILL / MOVE TO PLANNING。保留跨内容/行为/Schema 一致性与可观察验收条件 | 独立 Handoff 岗位、巨大交付包、候选 Manifest 和开发跨仓库翻译 | 一致性 Skill＋`planning/pages/<ID>.md`，一期提取必要内容 | 规格到实际动作/Schema 双向核对；合同澄清不冒充运行缺陷已修复 |
| gate9-read-only-acceptance | KEEP AS AGENT，大幅重写。真实运行观察、独立性、问题归属、修复复验、未测范围 | D16/D32、Gate8回执、四层状态及旧静态/Next 预检；不复制第二套状态系统 | `A:runtime-reviewer`＋`S:wordpress-runtime-verification`，一期 | 对指定 WP 页面和版本独立检查；表单仅在批准本地夹具下实际测试，不发邮件；环境不足说明缺哪项 |
| internal-link-review | MERGE 为专项范围，先保留方法。规划关系与运行落点分开、共享同因问题一次报告 | Gate 6/8/9 时点、独立岗位和固定 site_scope；普通站点无需发明多站隔离 | `A:runtime-reviewer` 的内链范围＋`S:internal-link-verification`，一期 | 发现 HTTP 200 但落到错误内容的链接；区分未开发目标与错误目标，不为消除 404 删除批准入口 |
| page-template-library | KEEP AS AGENT 候选。区分骨架、行业、品牌及业务绑定；只有结构/任务变化才新增模板 | D16 库路径、复杂入库链、把单页通过当跨站可用 | `A:template-librarian`；未来必要时 `S:page-template-extraction`，第二网站/明确模板提取任务再做 | 对两个实际页面判断共性与反例，剥离品牌和产品事实；未经复用测试不得标通用稳定 |

## 4. 10 个 Skill 逐项迁移

源文件为 `skills/<旧名称>/SKILL.md`。KEEP METHOD 表示保留方法并适配，**没有任何源文件建议逐字原样激活**。

| 旧名称 | 处置 / 候选目标 | 保留方法 | 必须删除或改写 | 时机与验证 |
|---|---|---|---|---|
| search-intent-evidence-analysis | KEEP METHOD → 同名 S | 原词/扩展分离、查询口径、代表正文、观察与解释分离、未知不补零 | Gate 文案、强制独立报告和旧输出目录；按实际工具能力保留查询归属，不机械固定调用次数 | 有搜索意图缺口时；验证定向 site 查询比例不能推出原词主导意图 |
| buyer-task-content-design | KEEP METHOD → 同名 S，保持草稿资格 | 买家判断、信息落点、真实行动后果、正文/内部规则分离、必要限定不能随精简丢失 | Gate/Manifest、每次必造方法报告；改为更新当前指定内容位置，不产生第二份长期正文 | 内容任务试验；先用 Documents，再用 Market 等不同任务验证，不能直接标 STABLE |
| responsive-wireframe-design | REWRITE → `S:responsive-structure-design` | 页面家族映射、长表/FAQ/表单语义、风险局部样例、成熟结构直接复用 | workset/input_index/evidence_index、旧冻结预检、禁止生产实现 | 复杂表格/表单才调用；在 WP 中测窄屏对应关系与错误提示，不强制独立线框资产 |
| brand-applied-visual-design | KEEP METHOD → 同名 S | 品牌用途映射、真实内容样例、实际背景对比度、媒体真实性、共享规则 | Gate 4A、单独样例交接报告与冻结身份；站点品牌值改成读取输入 | 新组件或品牌差异时；不同背景/错误态仍可辨，示意图不得暗示企业实景或认证 |
| full-page-visual-composition | REWRITE → 同名 S | 完整阅读路径、真实长内容、全部实例、状态、媒体与 Footer 衔接 | 独立 HTML 原型交付、4B、正式 PNG 配额及禁止生产实现；1440/768/390、44px 作为当前站标准而非所有站硬编码 | 有整页设计工作才调用；直接作用于 WP 页面；截图只作检查证据，不再成为第二网站 |
| layout-interaction-verification | KEEP METHOD → 同名 S | 字串存在≠可见、无横溢出≠无裁切、图像≠键盘行为；关系/几何/状态、按变更复验 | Gate 特定引用、workset/freeze 索引、每次强制 JSON 或同范围重复截图 | 一期；用长字段、隐藏溢出、不可见焦点等反例证明能发现问题；明确自检或独立检查 |
| internal-link-verification | KEEP METHOD → 同名 S | 独立应检集合、链接实例、重定向/锚点/参数、落点语义、孤立页与采购路径 | Gate 触发时点、Manifest、硬编码多站 scope、额外独立报告 | 一期；从 SITE_MAP 和本批页面范围开始，对照 DOM/HTTP；不把 sitemap 等同买家入口 |
| page-contract-consistency-review | KEEP METHOD → 同名 S | 任务→内容、命题→来源、可见→Schema、CTA→接收→结果双向核对；用户新决定按范围覆盖 | Gate 6 FAST_PATH、交付组合、旧状态；保留源可追溯但不把 hash 当运行许可 | 一期；向审查样本植入事实扩大或 Schema 多出关系，必须定位到依据与实际输出 |
| runtime-implementation-verification | REWRITE → `S:wordpress-runtime-verification` | 源码/HTTP/浏览器/模拟证据分开、真实数据和接收结果、条件分支、环境版本与定向复验 | Gate8 Manifest、Next marker、旧静态制品协议、CMS→GraphQL→Next、通知数据块、四层状态及证据 HEAD | 一期；改成 WP 数据/后台→PHP→HTTP→浏览器；引用既有修订、ownership、依赖测试，不能只测首页 200 |
| development-delivery-specification | REMOVE FROM DAILY FLOW / MOVE TO PLANNING | 输入来源、业务规则、未决依赖、失败行为、可观察验收条件 | 独立 Skill、巨大交付包、原型冻结资产清单、跨仓库接收协议；不复制正文 | 将必要字段用于现有 Page Spec；验证实现无需猜字段上限/成功含义，并且未增加第二份 Brief |

## 5. 支持资产不跟目录整包迁入

| 原资产 | 处置与理由 |
|---|---|
| `agents/project-orchestrator/references/{handoff-contract,orchestration-sop,gate-routing}.md` | 旧治理留原仓库；提取“只恢复必要上下文、尊重已有授权、继续无关工作”至现有规则即可，不搬合同链 |
| `agents/page-template-library/references/catalog-contract.md` | 延后；提取适用任务、数据接口、业务绑定、验证范围和使用权，取消 D16 路径与旧入库流程 |
| `skills/layout-interaction-verification/references/core-risk-model.md` | 改写后候选保留：核心检查与页面专属风险；站点阈值读取 DESIGN_SYSTEM/任务，不写成普遍标准 |
| 同目录 `independent-visual-review.md`、`gate4-self-check.md` | 合并必要内容到简短 review reference：独立身份、首次覆盖、定向复验和截图触发；不带 Gate 接收字段 |
| 同目录 `gate3-structure-scope.md` | 旧阶段配置留原仓库；结构/视觉风险划分可提取，不新设结构关 |
| `responsive-wireframe-design/references/preflight-freeze-evidence.md`、`scripts/check-preflight-record.mjs` | 留原仓库，不复制冻结协议检查器 |
| `runtime-implementation-verification/references/gate9-method-integration.md` | 提取证据类型区别与复用条件；重写为 WordPress 方法配合，不复制共同 evidence_index |
| 同目录 `scripts/validate_evidence_manifest.py`、`gate9_preflight.py`、`validate_static_artifact.py` | 留原仓库；检查器绑定旧交接/制品协议，不为复用脚本保留已取消流程 |
| 同目录 `tests/test_gate9_tools.py`、`test_static_artifact.py` | 不迁入；它们验证旧检查器，不是 WordPress 网站验收。新方法优先消费本仓库适用测试 |
| 各目录 `history/` 和两个 README | 保留原仓库供追溯；新入口按实际迁入能力重写，不携带旧数量、旧状态和不可达链接 |

支持资料此轮完成用途和耦合盘点，未执行旧脚本；将来提取 references 时仍须完整读取拟采用段落及相关依赖，不能仅凭此表直接复制。

## 6. 新增 WordPress 方法的实际依据

| 候选 | 从当前项目提取什么 | 验证材料与边界 |
|---|---|---|
| `S:wordpress-content-modeling` | Core 类型选用；Taxonomy 事实关系、Meta 展示说明、Discovery 导航规则分开；身份与来源分离；修订数据完整性 | `planning/CONTENT_MODEL.md`；`tests/product-model.php`、`hardening-revision.php`、`hardening-relationships.php`、`ownership-identity.php`。这些是实现证据，不是新 Skill 已验证证明 |
| `S:wordpress-direct-build` | 在真实 WP 中建造；Theme 表现、Plugin 领域逻辑；复用 Core，增量 import 保留编辑；最少 PHP/CSS/JS；先代表页再扩批 | 现有 `wp-content`、`scripts`、`tests/cms-import.php`、`hardening-ownership.py`、`hardening-dependency.py`、`hardening-activation.php`。不复制固定项目名、路径、客户事实或凭据到通用方法 |
| `S:wordpress-runtime-verification` | 上表旧 runtime 方法重写，结合本地修订/关系/导入/插件依赖实际链路 | 现有 HTTP/浏览器测试和针对性 PHP 测试；数据库写入测试串行、仅本地并恢复夹具；邮件仍在后续范围 |

测试脚本继续由项目 `tests/` 维护，Skill 指导选择及解释结果，不另复制一套脚本。新站没有这些脚本时按其模型选择检查，不能依赖 D33 路径或宣称同样覆盖。

## 7. 建议实施顺序与停止条件

**一期：2 个 Agent＋6 个 Skill，分两步落地。** 这是根据当前任务的建议上限，不是每页必经调用链。

1. 先整理 `wordpress-content-modeling`、`wordpress-direct-build`、`wordpress-runtime-verification`，用一个获授权剩余页面实际试验。方法先标 DRAFT；不改已完成网站来凑验证。
2. 加入 `content-reviewer`、`runtime-reviewer` 两个独立职责配置，以及 `page-contract-consistency-review`、`layout-interaction-verification`、`internal-link-verification` 三个方法。让不同于实现作者的实例检查同一真实页面；方法共用一个有范围和证据的审查结果，不每个 Skill 产一份报告。

Documents/文件申请可作为首个候选：能检验事实边界、申请语义、产品上下文和接收结果。本表不授权开始该页面，也不扩大到邮件。买家内容方法可在该内容任务中作为下一项 DRAFT 试验，不能默认为一期已就绪依赖。

研究、事实、战略、架构 Agent 及其方法，等新站、新事实或明确研究缺口出现再迁。视觉制作三个方法按真实设计风险再迁。模板库和 starter 等第二网站或明确提取任务再做，不生成空目录。

总控/批次计划先由主任务承担；不要求每页创建专家团队。子 Agent 存在不等于自动委派授权，调用须遵守当前任务的用户指令与环境规则。审查方法可用于自检，但同一作者换角色名不能算独立审查。

每个首次迁入能力只验证四件事：

1. **能发现：** 新会话实际列出正确元数据/角色，名称无意外冲突；普通文档不冒充注册配置。
2. **会按需调用：** 正例任务能使用，已有批准输入的普通改动不会触发整套研究；不要依靠每页加载所有文件。
3. **遵守边界：** 原生 WP、事实/关系/授权正确；无旧 Gate 文件依赖，不产生重复正文/状态，不执行未授权邮件或发布。
4. **结果有效：** 对实际指定版本给出可复核观察，能识别有代表性的反例，修复后只补受影响检查；未测如实写出。

DRAFT → TESTED 需要新方法本身实际使用的任务、版本、结果与局限。跨站复用通过后才考虑 STABLE，第二网站成功也只能覆盖已验证范围。成熟度写在能力自己的说明里，不新增中央 Manifest 或升级流水线。

本表完成即停止能力架构设计。确认后按一期做最小实现与试验，试验发现具体缺口才调整；不以先完成全部候选能力为继续建站条件。

## 8. 源文件索引

以下链接固定到本轮实际源提交，避免未来源文件变化后混淆本表依据。旧内容仅作分析材料，不作为本仓库运行指令。

- [agents/business-strategy-positioning/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/business-strategy-positioning/agent.md)
- [agents/enterprise-product-facts/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/enterprise-product-facts/agent.md)
- [agents/gate1-execution/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate1-execution/agent.md)
- [agents/gate2-execution/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate2-execution/agent.md)
- [agents/gate2-review/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate2-review/agent.md)
- [agents/gate3-execution/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate3-execution/agent.md)
- [agents/gate4-complete-visual/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate4-complete-visual/agent.md)
- [agents/gate4-execution/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate4-execution/agent.md)
- [agents/gate5-execution/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate5-execution/agent.md)
- [agents/gate5-independent-visual-review/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate5-independent-visual-review/agent.md)
- [agents/gate6-review-delivery/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate6-review-delivery/agent.md)
- [agents/gate9-read-only-acceptance/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/gate9-read-only-acceptance/agent.md)
- [agents/internal-link-review/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/internal-link-review/agent.md)
- [agents/market-customer-competition/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/market-customer-competition/agent.md)
- [agents/page-template-library/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/page-template-library/agent.md)
- [agents/production-preparation/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/production-preparation/agent.md)
- [agents/project-orchestrator/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/project-orchestrator/agent.md)
- [agents/site-architecture-buyer-journey/agent.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/agents/site-architecture-buyer-journey/agent.md)
- [skills/brand-applied-visual-design/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/brand-applied-visual-design/SKILL.md)
- [skills/buyer-task-content-design/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/buyer-task-content-design/SKILL.md)
- [skills/development-delivery-specification/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/development-delivery-specification/SKILL.md)
- [skills/full-page-visual-composition/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/full-page-visual-composition/SKILL.md)
- [skills/internal-link-verification/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/internal-link-verification/SKILL.md)
- [skills/layout-interaction-verification/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/layout-interaction-verification/SKILL.md)
- [skills/page-contract-consistency-review/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/page-contract-consistency-review/SKILL.md)
- [skills/responsive-wireframe-design/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/responsive-wireframe-design/SKILL.md)
- [skills/runtime-implementation-verification/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/runtime-implementation-verification/SKILL.md)
- [skills/search-intent-evidence-analysis/SKILL.md](https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/skills/search-intent-evidence-analysis/SKILL.md)
