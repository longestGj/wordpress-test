# TiO2 Malaysia 网站 PRD V0.4

## 0. 文档信息

| 项目 | 内容 |
|---|---|
| 文档名称 | TiO2 Malaysia 网站产品需求文档 |
| 版本 | V0.4 |
| 日期 | 2026-08-29 |
| 状态 | 已确认页面—关键词架构基线 |
| 适用对象 | 网站总控、关键词研究、内容策划、首页开发、产品页开发、质量审查 |

### 0.1 版本管理规则

1. 本版本记录截至当前已经确认的内容。
2. 已发布版本不回写、不覆盖。
3. 后续每轮讨论形成的新决定，复制上一版本后升级为下一个版本。
4. 每个新版本必须保留变更记录，注明新增、修改、废止和仍未纳入的内容。
5. 未经确认的方案不得写成正式需求，只能列入后续版本候选范围。

## 1. 项目目标

建设一个以马来西亚原产地为核心竞争优势、面向指定国际市场获取 B2B 采购询盘的钛白粉专业网站。

网站不是 mytio2.com 的简单复制，也不是以反倾销为唯一主题的资讯站。它的核心任务是：

> 以 Malaysia Origin 建立差异化认知，以目标市场承接搜索需求，以产品、应用、文件和供应能力推动采购转化。

## 2. 品牌与运营主体

- 前台品牌：TiO2 Malaysia。
- 运营主体：IKHLAS TITANIUM (MALAYSIA) SDN. BHD.。
- 品牌关系：前台以 TiO2 Malaysia 独立呈现，同时以适度可见的方式说明运营主体。
- 产品关系：新网站与 mytio2.com 使用相同的产品体系，市场定位不同。

## 3. 战略定位

### 3.1 核心定位

Malaysia-Origin Titanium Dioxide Supply Platform。

### 3.2 核心价值

- Genuine Malaysia Origin
- Stable TiO2 Supply
- Origin Documentation
- Batch Traceability
- Technical and Export Document Support
- Alternative Supply Source

### 3.3 表达边界

- 反倾销、关税和贸易措施是目标市场的采购背景，不是网站品牌本身。
- 不使用“规避关税”“绕过贸易措施”等表达。
- 不对尚未核实的政策、认证、库存、交期和物流能力作出承诺。
- 贸易和法规内容必须依据官方来源，并显示资料更新时间。

## 4. 目标市场

V0.1 确认的目标市场为：

1. European Union
2. United Kingdom
3. India
4. Brazil

EU 战略上覆盖 EU27，第一阶段单独建设以下六个重点国家页面：

- Germany
- Italy
- Spain
- Poland
- Netherlands
- Belgium

其他 EU 国家仍属于可服务范围，但 V0.1 不要求全部建立独立页面。

## 5. 双站职责与关键词归属

### 5.1 新网站职责

- 主攻 EU、UK、India、Brazil 的市场搜索流量。
- 主攻 Malaysia-origin、alternative-origin 和目标市场采购相关搜索需求。
- 承担全部 14 个产品型号的主要 SEO 与询盘转化任务。
- 通过 Market、Product、Application、Documents 和 Resources 形成完整采购路径。

### 5.2 mytio2.com 职责

- 保留公司、工厂和既有产品体系展示属性。
- 可以正常承接自然询盘。
- 不主动建设与新网站相同的目标市场内容网络。

### 5.3 防止关键词内耗

- 两个网站不得同时建设相同市场意图的页面并主动竞争。
- 新网站拥有 14 个型号词在目标市场中的主要内容权。
- 新网站每个型号只建立一个权威产品页，不按国家复制产品详情页。

## 6. 网站架构原则

网站继续采用 Market-First Hybrid Architecture，但不再设置独立的 Malaysia Origin 一级栏目。

### 6.1 Header 导航

Header 必须包含以下链接，并将 Home 作为第一个文字导航项：

```text
Home | Markets | Products | Applications | Documents | Resources | About | Request a Quote
```

| Header 项 | 目标 | 职责 |
|---|---|---|
| Home | `/` | 返回首页并承接网站核心定位 |
| Markets | Market 总入口 | 承接 EU、UK、India、Brazil 采购需求 |
| Products | Products 总入口 | 展示产品分类、工艺页和 14 个型号 |
| Applications | Applications 总入口 | 承接应用场景搜索与产品选型 |
| Documents | Documents & Compliance | 承接技术、质量、原产地和合规文件需求 |
| Resources | Resources 总入口 | 承接采购指南、技术指南和市场贸易更新 |
| About | `/about/` | 说明平台、运营主体、Malaysia origin、生产与追溯证据 |
| Request a Quote | 询价入口 | 作为 Header 主要转化按钮 |

Logo 可以继续链接首页，但不能替代可见的 Home 文字链接。

### 6.2 顶层架构

```text
Home
→ Markets
→ Products
→ Applications
→ Documents & Compliance
→ Resources
→ About
→ Request a Quote / Request a Sample
```

| 栏目 | 核心职责 |
|---|---|
| Home | 表达 Malaysia-origin TiO2 核心定位，并承接全站主要入口 |
| Markets | 承接不同市场的供应商搜索和采购决策 |
| Products | 承接产品类别、工艺和 14 个具体型号的采购需求 |
| Applications | 承接涂料、塑料、色母粒、油墨、造纸等使用场景 |
| Documents & Compliance | 说明可提供的技术、质量、原产地及合规文件 |
| Resources | 承载采购指南、技术指南、替代来源指南和市场贸易更新 |
| About | 说明平台、运营主体、Malaysia origin、生产、质量、追溯和企业背书 |
| Conversion | 承接询价、样品和文件申请 |

### 6.3 About 页面内容

Malaysia Origin 不再作为独立导航栏目或独立一级落地页，其内容整合进单一 `/about/` 页面。

About 页面至少包含：

1. TiO2 Malaysia 是什么。
2. 与 IKHLAS TITANIUM (MALAYSIA) SDN. BHD. 的运营关系。
3. Malaysia manufacturing 与原产地说明。
4. 生产、包装、批次和出口环节的可追溯说明。
5. Certificate of Origin 及其他原产地文件概览。
6. 质量、技术支持和出口协调能力。
7. Documents、Contact 和 Request a Quote 的入口。

原产地文件的申请和详细管理仍由 Documents & Compliance 栏目承担；About 只负责建立事实与信任。

### 6.4 原产地关键词重新归属

关键词研究中的 ORIGIN-01 和 ORIGIN-02 原本映射到独立 Malaysia Origin 页面。V0.3 对其页面归属作如下覆盖：

| 搜索意图 | 代表关键词 | 新承接页 |
|---|---|---|
| 马来西亚钛白粉与供应商搜索 | `malaysia titanium dioxide`、`malaysia titanium dioxide supplier` | Home |
| 生产、原产地和追溯证明 | `malaysia origin titanium dioxide`、`made in malaysia titanium dioxide`、`titanium dioxide traceability malaysia` | About |
| 原产地文件申请 | `malaysia coo titanium dioxide`、country-of-origin document intent | Documents & Compliance |
| 替代来源研究 | `non china titanium dioxide`、`alternative titanium dioxide supplier` | Resources 中的独立采购指南 |

关键词研究 CSV 保留为原始研究记录，不回写覆盖。发生页面映射冲突时，以本 PRD 的最新批准版本为实施依据。

### 6.5 页面—关键词主表

开发级页面—关键词映射保存在：

`keyword-research/11_page_keyword_master.csv`

该文件不覆盖关键词研究原始输出，而是把关键词研究、PRD V0.3 页面架构和 14 个型号页要求合并成一张可供内容、SEO 和开发共同使用的页面级主表。

#### 覆盖范围

| 项目 | 数量 |
|---|---:|
| 页面记录 | 54 |
| 有独立 SEO 主关键词的页面 | 48 |
| 不设置独立主关键词的导航或工具页 | 6 |
| 已批准页面映射 | 31 |
| 规划中或暂定 URL | 20 |
| 新页面候选 | 3 |

按栏目分布：Home 1、About 2、Markets 12、Products 17、Applications 6、Documents 4、Conversion 3、Resources 9。

#### 字段规范

主表包含以下字段：

```text
page_id
section
page_name
url
page_type
market
language
primary_keyword
secondary_keywords
search_intent
buyer_stage
source_cluster
page_role
excluded_keywords
cannibalization_boundary
priority
mapping_status
verification_status
notes
```

#### 使用规则

1. 每个 SEO 获取页只能拥有一个主关键词。
2. 主关键词不得在两个可索引页面之间重复。
3. 辅助关键词必须服务于同一搜索意图，不得为了覆盖词量混入其他页面职责。
4. `excluded_keywords` 和 `cannibalization_boundary` 是内容开发的硬边界。
5. 导航页、Contact 和纯工具页可以使用 `NO_PRIMARY_KEYWORD`，不得强行为其创造搜索词。
6. 14 个型号页使用 `型号 + titanium dioxide` 作为主关键词；这些词来自已批准的产品架构，不代表已有搜索量、KD 或 CPC 数据。
7. `APPROVED_*` 页面可以按当前架构开发；`PROVISIONAL_URL`、`PLANNED_*` 和 `NEW_PAGE_CANDIDATE` 不得被误认为最终 URL 或已批准内容页面。
8. `FACT_EVIDENCE_REQUIRED`、`TECHNICAL_VERIFICATION_REQUIRED`、`OFFICIAL_SOURCE_UPDATE_REQUIRED` 等状态只冻结相关事实字段，不阻塞其他页面和公共组件。
9. 关键词研究 CSV 保留为研究证据；本主表负责实施映射。若最新 PRD 改变页面架构，必须在同一版本同步更新本主表。

#### 关键页面归属

| 页面 | 主关键词或状态 |
|---|---|
| Home | `malaysia titanium dioxide` |
| About | `malaysia titanium dioxide manufacturer` |
| Markets Hub | `NO_PRIMARY_KEYWORD` |
| EU | `titanium dioxide supplier europe` |
| UK | `titanium dioxide supplier uk` |
| India | `titanium dioxide supplier india` |
| Brazil EN | `titanium dioxide supplier brazil` |
| Brazil PT-BR | `fornecedor de dióxido de titânio`，URL 暂定 |
| Products Hub | `titanium dioxide pigment`，并承接 rutile 词组 |
| Chloride Process | `chloride process titanium dioxide` |
| Sulfate Process | `sulfate process titanium dioxide` |
| 14 个型号页 | 各自的 `型号 + titanium dioxide` |
| Applications | 分别承接 coatings、plastics、masterbatch、printing inks、paper 泛应用词 |
| Documents | 分别承接 REACH、TDS/SDS/COA、COO 词组 |
| Resources | 承接 non-China、process comparison、brand alternative 和 trade-update 研究意图 |
| RFQ | `titanium dioxide quote supplier` |

#### 质量检查基线

V0.4 主表必须保持：

- `page_id` 无重复。
- URL 无重复。
- SEO 主关键词无重复。
- 所有记录都有页面职责、映射状态和验证状态。
- 导航或工具页必须明确标记 `NO_PRIMARY_KEYWORD`。
- 原 Malaysia Origin 词簇不得继续映射到已经删除的独立栏目。

## 7. 产品范围

### 7.1 独立产品页要求

以下 14 个产品全部需要独立详情页：

- M-350
- M-510
- M-896
- M-996
- M-2196
- M-895
- M-200
- M-108
- M-210
- M-340
- M-886
- M-52
- M-2377
- CR-901

### 7.2 产品页原则

- 每个产品仅建立一个全站权威页面。
- 产品页承担型号关键词的主要 SEO 责任。
- Market 页面与 Application 页面根据实际需求链接到相关产品页。
- 不建立“14 个产品 × 4 个市场”的重复页面矩阵。
- M-2377 的工艺归类和正式产品定位必须在技术资料确认后再锁定。

产品分组、URL、主关键词和页面职责见第 9 节；统一产品页模板和完整内部链接矩阵将在后续版本确认。

## 8. Market 模块

### 8.1 Market 页定位

Market 页面是面向当地采购商的采购落地页，不是国家百科、普通地名 SEO 页面或纯贸易政策文章。

它必须回答五个问题：

1. 为什么该市场的采购商需要考虑马来西亚原产钛白粉？
2. 哪些产品和应用适合该市场？
3. 可以提供哪些技术、质量、原产地和合规文件？
4. 供应与出口支持如何进行？
5. 如何申请报价、文件或样品？

### 8.2 Market 页面层级

```text
Markets
├── European Union
│   ├── Germany
│   ├── Italy
│   ├── Spain
│   ├── Poland
│   ├── Netherlands
│   └── Belgium
├── United Kingdom
├── India
└── Brazil
```

### 8.3 Market 总入口内容

- 四个目标市场概览。
- Malaysia-origin supply 对各市场的采购价值。
- 各市场主要应用、文件和供应关注点。
- 市场入口卡片。
- 统一询价入口。

### 8.4 单个 Market 页标准内容

1. **市场化首屏**：Malaysia origin、TiO2 supplier、目标市场、核心应用和询价行动。
2. **采购背景**：供应来源多元化、稳定供应、原产地与追溯价值。
3. **推荐产品**：按当地应用推荐相关产品，不平铺或复制全部产品内容。
4. **主要应用**：展示当地重点应用，并链接到全站 Application 页面。
5. **文件与合规支持**：TDS、SDS、COA、Certificate of Origin、批次追溯及适用声明。
6. **原产地与供应链**：生产地、运营与出口主体、原产地证明、包装、运输支持和样品流程。
7. **贸易背景摘要**：只保留影响采购决策的简短信息，详细内容链接到 Resources。
8. **市场 FAQ**：回答供应、产品匹配、文件、样品、报价和原产地证明问题。
9. **市场化询价表单**：收集目的地、应用、型号或指标、数量、包装、所需文件及样品需求。

### 8.5 各市场差异化重点

| 市场 | 内容重点 |
|---|---|
| European Union | EU 供应商搜索、Malaysia origin、EU 合规、REACH、原产地文件和 EU 贸易背景 |
| EU 国家页 | 当地工业与应用、采购特点、物流目的地；统一法规和贸易内容由 EU 总页承担 |
| United Kingdom | 独立于 EU 的采购与合规环境、供应来源、文件和英国市场贸易更新 |
| India | Supplier、manufacturer、price、paint、plastics、masterbatch，突出产品匹配与询价 |
| Brazil | Supplier、importer、price、coatings、plastics，并支持葡萄牙语采购搜索需求 |

Market 页面可以共享结构，但不得通过简单替换国家名称批量生成内容。每个页面必须具有真实的当地采购、应用、文件和供应信息。

## 9. Products 第二层架构

### 9.1 架构方案

采用“扁平型号 URL + 双维度分类”。

- 14 个型号使用稳定的扁平 URL：`/products/{grade}/`。
- 产品总页按主要应用展示四个产品组。
- 氯化法与硫酸法各建设一个可索引的工艺聚合页。
- 应用分组用于展示和筛选，不在 Products 下建立与 Applications 重复的应用 SEO 页面。
- 型号的主要应用或工艺发生调整时，不改变型号 URL。

### 9.2 Products 页面层级

```text
Products
├── All Titanium Dioxide Grades
├── Chloride Process TiO2
├── Sulfate Process TiO2
├── Coatings Grades
│   ├── M-350
│   ├── M-510
│   ├── M-896
│   ├── M-996
│   ├── M-2196
│   └── M-895
├── Plastics & Masterbatch Grades
│   ├── M-200
│   ├── M-108
│   ├── M-210
│   ├── M-340
│   └── M-886
├── Inks & Multi-Application Grades
│   ├── M-52
│   └── M-2377
└── Specialty Grade
    └── CR-901
```

### 9.3 产品聚合页

| 页面 | URL | 主关键词 | 页面职责 |
|---|---|---|---|
| 产品总页 | `/products/` | `titanium dioxide pigment` | 承接 rutile、supplier、grades 和 grade selection 等泛产品需求，展示 14 个型号并帮助买家选型 |
| 氯化法产品页 | `/products/chloride-process-titanium-dioxide/` | `chloride process titanium dioxide` | 解释氯化法产品特点，并聚合已经确认的氯化法型号 |
| 硫酸法产品页 | `/products/sulfate-process-titanium-dioxide/` | `sulfate process titanium dioxide` | 同时承接 `sulfate` 与 `sulphate` 拼写，并聚合硫酸法型号 |

`rutile titanium dioxide`、`rutile TiO2 supplier` 和相近词由产品总页承接，不再建立独立 Rutile 页面。

### 9.4 14 个型号的归类、URL、主关键词与职责

| 型号 | 主分类 | 工艺 | URL | 主关键词 | 页面职责 |
|---|---|---|---|---|---|
| M-350 | Coatings | Chloride | `/products/m-350/` | `M-350 titanium dioxide` | 通用型氯化法牌号；承接装饰涂料、工业漆、汽车漆和油墨选型 |
| M-510 | Coatings | Chloride | `/products/m-510/` | `M-510 titanium dioxide` | 多应用型牌号；重点说明建筑涂料、汽车漆及其他体系中的适用性 |
| M-896 | Coatings | Chloride | `/products/m-896/` | `M-896 titanium dioxide` | 工业及耐候涂料牌号；聚焦工业、卷材、防护、船舶和外用涂料 |
| M-996 | Coatings | Sulfate | `/products/m-996/` | `M-996 titanium dioxide` | 硫酸法耐久涂料牌号；聚焦工业、粉末和建筑涂料 |
| M-2196 | Coatings | Sulfate | `/products/m-2196/` | `M-2196 titanium dioxide` | 硫酸法工业及粉末涂料牌号；页面需说明与 M-996 的选择差异 |
| M-895 | Coatings | Chloride | `/products/m-895/` | `M-895 titanium dioxide` | 建筑及工业涂料牌号；聚焦分散、遮盖、光泽和耐候方向 |
| M-200 | Plastics | Chloride | `/products/m-200/` | `M-200 titanium dioxide` | 耐久户外塑料牌号；聚焦 uPVC 型材、板材、户外制品和 PVC 压延膜 |
| M-108 | Masterbatch | Sulfate | `/products/m-108/` | `M-108 titanium dioxide` | 高热稳定色母及塑料牌号；承接色母、配混、聚烯烃和 PVC 薄膜 |
| M-210 | Masterbatch | Chloride | `/products/m-210/` | `M-210 titanium dioxide` | 聚烯烃色母及工程塑料牌号；覆盖 PE、PP、ABS、PS 及橡胶 |
| M-340 | Plastics | Chloride | `/products/m-340/` | `M-340 titanium dioxide` | 高浓度色母和薄膜主力牌号；突出分散、白度和耐高温黄变 |
| M-886 | Plastics | Chloride | `/products/m-886/` | `M-886 titanium dioxide` | 高白度塑料牌号；聚焦色母、高温挤出、流延膜和工程塑料 |
| M-52 | Printing Inks | Sulfate | `/products/m-52/` | `M-52 titanium dioxide` | 油墨专用牌号；聚焦油墨、罐听涂料、高光泽和低磨蚀方向 |
| M-2377 | Multi-Application | Technical verification required | `/products/m-2377/` | `M-2377 titanium dioxide` | 暂按多应用产品管理；只公开经过技术资料确认的应用、工艺和指标 |
| CR-901 | Specialty | Vapor-phase oxidation | `/products/cr-901/` | `CR-901 titanium dioxide` | 高纯特种产品；聚焦电子陶瓷、光学玻璃、电池材料及专业材料应用 |

### 9.5 型号页关键词规则

每个型号页采用以下关键词层级：

1. 主关键词：`型号 + titanium dioxide`。
2. H1 语义：`型号 + rutile titanium dioxide + primary application`。
3. 辅助语义：型号与 `TiO2`、工艺、TDS、specifications、sample、supplier 的组合。

型号页不得主攻 `titanium dioxide for coatings`、`titanium dioxide for plastics` 等泛应用词，这些词由对应 Application 页面承接。

### 9.6 型号页职责

每个型号页负责：

1. 说明型号、金红石类型和已确认工艺。
2. 明确主要应用、适合与不适合的体系。
3. 展示经过技术资料确认的关键指标。
4. 提供 TDS、SDS、COA、COO 等文件申请入口。
5. 链接到对应工艺页、Application 页面和相关 Market 页面。
6. 推荐相关型号，并说明有证据支持的选择差异。
7. 提供市场、数量、包装、目的港、样品和报价入口。
8. 说明 Malaysia origin 和可提供的供应支持。
9. 使用新网站的市场采购视角重新编写，不复制 mytio2.com 现有正文。

### 9.7 非阻塞质量与冲突记录

以下问题已经登记，但不阻塞产品路由、产品总页、工艺页、统一模板和其他已确认产品页面的开发。

#### M-2377

- 当前公开资料对 M-2377 的工艺和应用存在冲突。
- URL、主关键词和 Multi-Application 暂定分类已经锁定，可以继续开发。
- 工艺、主应用、推荐与不推荐场景在最新 TDS 完成技术确认前不公开。
- 页面可先接入已验证字段；未验证模块保持不渲染，不使用猜测或占位文案。

#### M-996 与 M-2196

- 当前公开描述对两者的差异说明不足。
- 两个独立 URL、关键词、分类和页面开发继续推进。
- 在技术资料确认表面处理、分散体系、耐候或选型差异前，不发布无证据的比较结论。
- 如果差异字段尚未批准，相关比较模块不渲染；不影响两个页面的基础资料、文件申请和询价功能。

#### 开发处理规则

- 数据字段必须支持 `verified`、`pending_verification` 和 `not_public` 三种状态。
- 只有 `verified` 字段可以进入公开页面。
- 单个产品的资料问题只冻结对应字段或模块，不阻塞其他产品和公共组件。
- 发现新冲突时登记来源、影响页面、临时处理和所需证据，不直接复制现有站点的矛盾内容。

## 10. 内容与转化路径

网站的主要访问路径为：

```text
市场或产品关键词
→ Market / Product / Application 页面
→ 产品与文件匹配
→ Request a Quote / Request a Sample / Request Documents
→ 有效采购询盘
```

有效询盘至少应尽可能收集：

- 目的国家或港口
- 应用领域
- 当前使用型号或目标技术指标
- 预计采购量
- 包装要求
- 所需文件
- 样品需求

## 11. V0.4 已确认范围

- 网站战略定位与品牌关系。
- 四个目标市场及六个 EU 重点国家。
- Market-First Hybrid Architecture。
- Header 设置可见的 Home 文字链接，并将其置于第一项。
- 删除独立的 Malaysia Origin 一级栏目，将其内容整合进 `/about/` 页面。
- 原产地关键词重新分配至 Home、About、Documents 与 Resources。
- 建立开发级页面—关键词主表，覆盖 54 个页面记录。
- 锁定 48 个 SEO 获取页的唯一主关键词和 6 个无主关键词的导航/工具页。
- 建立每页的排除关键词、内耗边界、映射状态和验证状态。
- 新网站与 mytio2.com 的职责和关键词边界。
- 14 个产品均建设独立产品页。
- Market 模块的页面定位、层级、标准内容和差异化原则。
- Products 的扁平 URL、四个展示分组和两个工艺聚合页。
- 14 个产品的主分类、URL、主关键词和页面职责。
- 型号词与泛应用词的 SEO 归属规则。
- M-2377、M-996 与 M-2196 的非阻塞质量处理规则。
- 贸易政策属于采购背景，详细内容归入 Resources。

## 12. 后续版本范围

以下内容未纳入 V0.4 的正式需求，将在后续版本逐项讨论并批准：

- 14 个产品页的统一内容模板和字段规范。
- Products、Applications、Markets、Documents 之间的完整内部链接矩阵。
- M-2377 经最新 TDS 证实后的工艺、主应用和产品对比信息。
- M-996 与 M-2196 经技术资料证实后的选型差异。
- Applications 栏目的详细页面体系。
- Documents & Compliance 的页面清单。
- Resources 的内容栏目和发布节奏。
- 全站 URL 规则、面包屑和非产品栏目的内部链接矩阵。
- Brazil 的完整语言与本地化实施方案。
- 询价、样品和文件申请表单的字段及流程。
- 页面级 SEO 元数据、结构化数据和验收标准。
- 首页与各栏目页的最终内容对接。

## 13. 参考资料

- `01Mylasiya的对应---00网站定位-2026-08-29.md`
- `keyword-research/01_keyword_master.csv`
- `keyword-research/02_keyword_clusters.csv`
- `keyword-research/03_keyword_architecture_map.csv`
- `keyword-research/11_page_keyword_master.csv`
- `homepage/03_results/03_homepage_input_requirements.md`
- `01Visio/TiO2_Malaysia_Visual_Standard_V1.0.md`

## 14. 变更记录

| 版本 | 日期 | 变更内容 |
|---|---|---|
| V0.1 | 2026-08-29 | 建立首个 PRD 基线，收录已确认的战略定位、市场、双站边界、14 个产品独立页面要求及 Market 模块设计 |
| V0.2 | 2026-08-29 | 增加 Products 第二层架构、14 个型号的分类、URL、主关键词、页面职责及非阻塞质量处理规则 |
| V0.3 | 2026-08-29 | 删除独立 Malaysia Origin 一级栏目，将其内容并入 About；Header 增加首位 Home 文字链接，并重新分配原产地关键词 |
| V0.4 | 2026-08-29 | 建立 54 条页面—关键词主表，锁定页面主关键词、辅助关键词、搜索意图、排除词、内耗边界、映射状态与验证状态 |
