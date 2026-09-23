# Home Visual Refinement Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task by task.

**Goal:** Improve the existing Home page's visual rhythm and material-led presentation without changing its copy, links, section order, or WordPress behavior.

**Architecture:** Keep the saved WordPress Home content intact. Refine the existing Home-only stylesheet and remove the conflicting Home hero runtime override. Verify against the actual public Home DOM by substituting local CSS in a browser; do not write to production.

**Tech Stack:** WordPress Core theme CSS, Playwright Chromium, local Git worktree.

**Spec:** User-supplied Home visual brief in this task; `planning/pages/HOME-001.md` defines the current Home structure.

## Global Constraints

- Preserve every existing Home text node, heading level, link destination, section, and section order.
- Keep the current TiO₂ material hero image, color family, Inter font, navigation, and mobile product accordion behavior.
- Scope visual changes to Home; avoid new imagery, decorative icons, heavy shadows, and motion.
- Do not deploy or write to the public site as part of this visual review.

## Review Focus

- At 1440, 1024, 768, 390, and 320 pixels, the page must have no horizontal overflow and legible text.
- The mobile RFQ closing section must be visible and its link usable.
- All 14 product grade links and the mobile accordion must remain functional.
- The Hero image must remain visible and its crop must retain the material focus.
- Home-only CSS must not change other page families.

---

### Task 1: Establish the visual and interaction baseline

- [x] Read the current Home spec, seed, theme stylesheet, runtime overrides, and shared header/footer styles.
- [x] Capture public Home screenshots at desktop and mobile widths and verify no baseline overflow.
- [x] Record the current text, link destinations, heading hierarchy, and ten-section order for final comparison.

### Task 2: Refine Home layout in the existing stylesheet

- [x] Make the Hero a dark, full-width composition with an integrated, right-bleeding material image.
- [x] Use spacing and typography to make Company, Products, Applications, Evaluation, Process, Resources, Documents, Markets, and RFQ visually distinct.
- [x] Replace repeated card treatments with dividers, technical rows, and editorial grids while preserving all content.
- [x] Update Home-only header/footer and mobile rules; show RFQ on mobile.
- [x] Remove only the runtime rule that forces a white Home Hero.

### Task 3: Verify and review

- [x] Run the existing Home product accordion browser tests.
- [x] Inject the changed Home CSS into the current public DOM without changing the site; inspect 1440, 1024, 768, 390 and 320 pixel screenshots and interactions.
- [x] Compare text, links, headings, and section order with the baseline; fix any drift or overflow.
- [x] Run `git diff --check`, review the CSS diff, and report remaining visual limits.
