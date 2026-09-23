# CONV-SAMPLE — Request a Sample

Status: ACCEPTED

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

已在隔离的本地 WordPress 实例实现原生 Page 与样品申请表。已验证未知 Grade、Other 应用补充说明、字段长度服务端校验且保留输入、私有记录与回执、1440/768/390 页面布局；产品页可带入已发布 Grade。数据库测试使用合成资料并清理。Gmail 通知已实现为保存后的独立步骤，发送状态与重试只在管理后台显示；2026-09-23 本机 Gmail TLS 与身份验证通过，三个申请页各一条真实通知均获 Gmail 接受（每条一次），重复提交与无效输入回归通过，测试记录已清理；用户已确认三封通知全部收到。正式发布仍须单独授权。

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Gmail notification code is implemented; local authentication and one SMTP-accepted notification per form passed on 2026-09-23; the user confirmed receipt of all three notifications. Runtime does not consult this specification or planning source hashes.

Integrated local acceptance 2026-09-23: the main `/request-sample/` Page passed SEO, owned-Page, link and 1440/768/390 browser checks. A new synthetic HTTP submission reached the browser-bound Thank You receipt; repeat POST reused that receipt, invalid email retained input, and an overlong evaluation objective produced an accessible field error without truncation. Test mail was intercepted, and the private record and duplicate claim were removed. The earlier real sample notification was confirmed received by the user. Local Page accepted; production publication still needs separate authorization.
