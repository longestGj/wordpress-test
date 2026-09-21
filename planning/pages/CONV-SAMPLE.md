# CONV-SAMPLE — Request a Sample

Status: PLANNED

URL: `/request-sample/`
Family: Sample conversion page · EN

## 页面职责

Capture qualified sample requests and application context.

## 内容与事实

- [Base content input (V0.5 overrides below)](<../inputs/pages/conversion/request-sample/04_planning/CONV-SAMPLE_CONTENT_ARCHITECTURE_V0.1.md>)
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide sample supplier
Title: Request a Titanium Dioxide Sample | TiO2 Malaysia
Meta: Request a Malaysia-origin titanium dioxide sample for technical evaluation by sharing the grade, application, destination and test objective for human review.
H1: Request a Titanium Dioxide Sample for Technical Evaluation

Keyword boundary: Sample page owns sample-action intent; grade pages own model intent and pass the selected grade into this workflow.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/CONV-SAMPLE_FULL_VISUAL_DESIGN_V0.5.md>)

- [CONV-SAMPLE_G5_DESKTOP_1440_PREFILLED_BUYER_CLEAN_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_DESKTOP_1440_PREFILLED_BUYER_CLEAN_V0.5.png>)
- [CONV-SAMPLE_G5_DESKTOP_1440_SUCCESS_BUYER_CLEAN_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_DESKTOP_1440_SUCCESS_BUYER_CLEAN_V0.5.png>)
- [CONV-SAMPLE_G5_DESKTOP_1440_UNPREFILLED_BUYER_CLEAN_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_DESKTOP_1440_UNPREFILLED_BUYER_CLEAN_V0.5.png>)
- [CONV-SAMPLE_G5_DESKTOP_INTERACTION_STATE_BOARD_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_DESKTOP_INTERACTION_STATE_BOARD_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_INTERACTION_STATE_BOARD_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_INTERACTION_STATE_BOARD_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_MENU_OPEN_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_MENU_OPEN_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_OTHER_APPLICATION_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_OTHER_APPLICATION_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_PREFILLED_BUYER_CLEAN_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_PREFILLED_BUYER_CLEAN_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_SERVICE_UNAVAILABLE_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_SERVICE_UNAVAILABLE_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_SUBMISSION_FAILURE_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_SUBMISSION_FAILURE_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_SUBMITTING_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_SUBMITTING_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_SUCCESS_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_SUCCESS_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_UNKNOWN_GRADE_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_UNKNOWN_GRADE_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_UNPREFILLED_BUYER_CLEAN_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_UNPREFILLED_BUYER_CLEAN_V0.5.png>)
- [CONV-SAMPLE_G5_MOBILE_390_VALIDATION_FOCUS_ERROR_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_MOBILE_390_VALIDATION_FOCUS_ERROR_V0.5.png>)
- [CONV-SAMPLE_G5_TABLET_768_UNPREFILLED_BUYER_CLEAN_V0.5.png](<../inputs/pages/conversion/request-sample/04_planning/visual-designs/assets/CONV-SAMPLE_G5_TABLET_768_UNPREFILLED_BUYER_CLEAN_V0.5.png>)

## 已批准覆盖与字段约束

V0.5 Full Visual Design 的正文覆盖 V0.1 中对应内容：Hero、提交 helper、成功/失败消息及 FAQ 免责声明位置以 V0.5 为准。提交前显示 Privacy Policy 说明和链接，不增加确认复选框。Destination 必填、可编辑；不推导物流或供应承诺。其余基础内容仍参考 V0.1。

以下只提炼已批准的数据约束，不继承旧 Gate、交接或 Next.js 实现机制。



| Payload key | Maximum characters |
|---|---:|
| `application_other` | 500 |
| `test_objective` | 2000 |
| `current_grade_or_target` | 1000 |
| `contact_name` | 120 |
| `company_organisation` | 200 |
| `business_email` | 254 |
| `destination_country_market` | 120 |
| `expected_project_annual_use` | 500 |
| `additional_context` | 2000 |

All over-limit errors must be accessible, name the affected field and preserve the buyer's input. Silent truncation is prohibited.


Source: [approved maximum-length contract §3](<https://github.com/longestGj/tio2mydesign/blob/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/pages/conversion/request-sample/06_handoff/CONV-SAMPLE_GATE7_MANIFEST_V0.2.md>), snapshot SHA-256 `5a8abfdb6a799cfa47e02a662202ffcdf4645a3ffd82bfe89007d9cce9325599`.

## 实现与验收

当前 WordPress 尚未实现。本页迁入已有策划输入，不继承其他旧项目的开发/上线状态。进入 READY 时确认本页行为、视觉补充和相关目标已经清楚；不重新调查已批准产品事实。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Email remains a later task. Runtime does not consult this specification or planning source hashes.
