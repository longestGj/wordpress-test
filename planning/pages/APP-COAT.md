# APP-COAT — TiO2 for Coatings

Status: ACCEPTED

URL: `/applications/titanium-dioxide-for-coatings/`
Family: Application landing page · EN

## 页面职责

Own generic Coatings use-case intent and recommend verified grades.

## 内容与事实

- [Current content input](<../inputs/pages/applications/coatings/04_planning/APP-COAT_GATE2_FULL_BUYER_CLEAN_COPY_V0.2.md>)
- [WordPress initialization seed](../../data/process-applications/coatings.json) — initialization only; preserve live editor changes.
- [Relationship boundaries](../RELATIONSHIPS.csv) — classification is not a performance or equivalence claim.
- [Approved fact decisions](../inputs/docs/architecture/EVIDENCE_GAP_USER_DECISION_REGISTER_V1.8.md) — applies only within the approved scope.

## SEO

Primary keyword: titanium dioxide for coatings
Title: Titanium Dioxide for Paints & Coatings | Grade Evaluation
Meta: Evaluate titanium dioxide for paints and coatings by formulation, dispersion and prepared-film optical properties. Compare candidate Grades using declared test conditions.
H1: Titanium Dioxide for Coatings

Keyword boundary: Application page owns generic use-case intent; grade pages own exact model intent and link back for broader selection.

## 视觉与补充资料

- [Visual reference](<../inputs/pages/applications/coatings/04_planning/gate4-v0.1/APP-COAT_GATE4_COMPLETE_VISUAL_V0.1.html>)

## 实现与验收

当前精修已在主本地站点验收；ACCEPTED 不代表正式上线或所有外部目标已连接。
Native WordPress Page rendered by the topic template; editor content remains authoritative after the exact-copy migration.

Review: approved content and facts; SEO; 1440/768/390; internal links; forms when applicable; keyboard/focus; revisions; relevant regression and code review.
Restrictions: do not invent origin, document availability, stock, certification, delivery or application claims. Research dates remain the source dates. No automatic taxonomy/copy/Discovery synchronization.

Release: requires explicit user authorization. Runtime does not consult this specification or planning source hashes.

## Coatings entry refinement (2026-09-23)

Purpose: serve both new-grade selection and incumbent replacement while preserving the H1, eight-grade mapping, technical body, request cards, endpoint table and bottom Technical sources. The Hero now explains system-specific evaluation for paints and coatings, then distinguishes new formulation work from controlled incumbent comparison. Similar TDS fields or one favourable result still do not establish equivalence.

The compact **Coatings Evaluation at a Glance** summary follows the Hero and precedes the technical body. Its six categories are Formulation (binder, vehicle, loading, additives), Dispersion (incorporation and process history), Optical (hiding, opacity, colour, undertone, gloss), Film (substrate, build, application, cure), Durability (exposure, weathering, method) and Grades to Review (the eight existing candidates across chloride and sulfate routes). These are evaluation inputs already explained in the approved Coatings body, not claims of product performance.

The RFQ card asks buyers without a selected Grade for known application, formulation, quantity, destination and evaluation requirements. Buyers considering several Grades can list the candidates in the request. The existing statement that an RFQ is not technical qualification, test approval or product equivalence remains. The optional Documents card link was left as designed.

The application-detail `ItemList` is derived from actual product links on each topic page and checks the product's live `product_application` taxonomy term before using the published, public Product permalink. This is shared across five application-detail pages; Coatings produces the eight unchanged Grade links. `WebPage`, `BreadcrumbList` and canonical remain. Taxonomy is authoritative; the Schema does not infer suitability or rank grades. Masterbatch's M-510 illustrates why the taxonomy, rather than the product-description list, must drive relationships: its approved public mapping and live taxonomy include Masterbatch, while the current product-description list does not include a Masterbatch paragraph.

The exact-copy migration and seed change only four passages: Hero text, its secondary CTA, the inserted summary and the RFQ field-label passage. Sections 02–08 and 10 (technical method, endpoint/grade tables, sources) have unchanged HTML content. No new technical performance claim or source-verification issue was identified from the approved content.

Validation: `tests/coatings-refinement-http.py` passes on both isolated preview and `http://localhost:8080`, covering the single H1, SEO/canonical, Hero intents, six summary categories, all eight unchanged Grade links and process classes, five application-detail ItemLists, RFQ wording, sources, endpoint table and 1440/768/390 layouts. An additional 320px keyboard check found no overflow and a visible focus outline on the Grade CTA. `tests/coatings-refinement-migration.php` passes against the isolated local database, including repeat-run preservation of unrelated editor content and rejection of mixed/edited Hero copy. After one main-local migration, a second run reported current content. Main-local `tests/process-applications-http.py`, `tests/batch-http.py` and `tests/planning-integrity.py` pass. Independent code review found migration and public-Schema edge cases; both were fixed and the recheck found no remaining Critical or Important issue.
