# TiO2 Malaysia Primary CTA Accessibility Addendum V1.0

## Control

| Field | Value |
|---|---|
| Date | 2026-09-02 |
| Status | `USER_APPROVED / ACTIVE_GLOBAL_OVERRIDE` |
| Decision source | Current project-control conversation: Option `A` |
| Parent standard | `TiO2_Malaysia_Visual_Standard_V1.0.md` |
| Review item | `LEGAL-PRIVACY-G4-CTA-CONTRAST-01` |

## Approved override

The parent standard’s filled Primary CTA rule is refined as follows:

| Token / use | Approved value | Text / pairing | Contrast |
|---|---|---|---:|
| Accessible Functional Teal | `#008078` | Filled CTA with white label; normal link text, focus indicators and interactive borders on white/light surfaces | `4.82:1` against white |
| Malaysia Teal | `#00A99D` | Decorative/large graphical accents and brand emphasis on Deep Navy or other verified dark surfaces | `5.86:1` against Deep Navy; not for functional elements on white/light surfaces |
| Primary Navy | `#062B5B` | White text where a Navy action or dark surface is required | `13.96:1` |

Filled Primary CTA buttons must use `#008078` with white text. Normal links, focus rings and interactive borders on white/light surfaces must also use `#008078`. `#00A99D` must not be used for normal-size text or functional UI boundaries on white/light surfaces.

## Scope

- Global visual token applying to all TiO2 Malaysia pages and shared components when they next enter design revision, handoff or implementation.
- Does not silently reopen already approved page content, routes, module order or gate status.
- Hover, active, disabled, link and focus states must be derived without falling below the applicable WCAG contrast requirement.
- Gate 7 contracts and external development must cite this addendum together with Visual Standard V1.0.

## Supersession boundary

Only the Primary CTA colour pairing is overridden. All other Visual Standard V1.0 colour, typography, card, imagery and button-shape rules remain active.
