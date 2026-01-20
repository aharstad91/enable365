# Session Log

> **Shared context file between Claude Chat and Claude Code**
> Updated continuously by both parties to stay in sync.

---

## Last Updated
- **When:** 2026-01-20
- **By:** Claude Code
- **What:** Debugging Logo Grid hover effects - ikke fungerende ennå

---

## Active Context

### Current Focus
- E365 Logo Grid - Feilsøking av hover-effekter (grayscale, scale, lift)

### Latest Changes
- Undersøkte Logo Grid template - hover-klasser genereres korrekt i PHP
- Problem: Hover-effektene vises ikke på frontend
- Mulig årsak: Tailwind-klasser ikke kompilert, eller CSS-spesifisitet-problemer

### Open Questions / Blockers
- **Logo Grid hover ikke fungerer** - trenger å verifisere:
  1. Er hover-klassene i kompilert CSS (`style.tailwind.css`)?
  2. Er det CSS-konflikter som overstyrer?
  3. Fungerer grayscale-filteret i det hele tatt?

### Important to Remember
- Enable365 is a WordPress theme for Microsoft 365 productivity apps
- Tech stack: WordPress, Tailwind CSS, Alpine.js, GSAP, ACF
- Run `npm run build` before committing (builds both frontend and editor CSS)
- WPML handles multilingual content
- Editor Tailwind uses `.acf-block-preview` scoping to avoid admin UI conflicts

---

## Changelog

### 2026-01-20

#### [Code] Logo Grid hover debugging (pågående)
- **Problem:** Hover-effekter (grayscale, scale, lift) fungerer ikke på Logo Grid
- **Undersøkt:**
  - Template.php genererer korrekte Tailwind-klasser basert på ACF-felt
  - Klasser inkluderer: `grayscale hover:grayscale-0`, `hover:scale-105`, `hover:shadow-lg hover:-translate-y-1`
- **Ikke løst ennå** - trenger videre debugging i neste sesjon
- **Neste steg:**
  1. Sjekk om klassene finnes i `style.tailwind.css`
  2. Kjør `npm run build` for å sikre kompilering
  3. Inspiser elementet i DevTools for å se hvilke CSS-regler som gjelder

---

### 2026-01-19

#### [Code] Scoped Tailwind CSS for Block Editor
- **Problem:** Loading Tailwind CSS in WordPress editor broke admin UI (previous attempts failed)
- **Solution:** Created scoped Tailwind build that only applies to `.acf-block-preview`
- **How it works:**
  1. Separate Tailwind config (`tailwind.config.editor.js`) with `important: '.acf-block-preview'`
  2. Separate input file (`src/editor.css`) with components and utilities
  3. Separate build script (`npm run build:editor`)
  4. Loaded via `enqueue_block_editor_assets` hook
- **Result:** Admin UI is stable, Tailwind utilities work inside ACF block previews
- **Files created:**
  - `tailwind.config.editor.js` - Scoped config
  - `src/editor.css` - Editor-only Tailwind input
  - `style.editor.css` - Compiled scoped CSS
- **Files modified:**
  - `package.json` - Added `build:editor` script, updated `build` to include both
  - `functions.php` - Added `enable365_enqueue_editor_tailwind()` function

#### [Code] Fix: Editor centering issue
- **Problem:** Blocks in WordPress editor were left-aligned instead of centered
- **Root cause:** `block-styles.css` had `.wp-block{max-width: 1440px!important;}` which:
  1. Set a max-width larger than or equal to the editor container
  2. Missing proper layout configuration in theme.json
- **Solution:**
  1. Removed the `.wp-block` max-width rule from `block-styles.css`
  2. Added proper layout config to `theme.json` with `contentSize: 1200px` and `wideSize: 1440px`
  3. Added `useRootPaddingAwareAlignments: true` and `appearanceTools: true`
- **Files modified:**
  - `template-parts/block/block-styles.css` - Removed problematic `.wp-block` rule
  - `theme.json` - Added layout settings

#### [Code] Fix: Reverted Tailwind editor loading
- **Problem:** Loading Tailwind CSS in WordPress block editor broke entire admin UI layout
- **Attempts that failed:**
  1. `add_action('enqueue_block_editor_assets', 'enable365_enqueue_tailwind', 20);`
  2. `add_theme_support('editor-styles'); add_editor_style('style.tailwind.css');`
- **Solution:** Removed editor-styles integration entirely - admin stability is priority
- **Note:** Block previews won't show Tailwind styling in editor, but will display correctly on frontend
- **Files modified:**
  - `functions.php` - Removed lines 4-5 (editor-styles)

#### [Code] E365 Logo Grid block
- **Created:** New block for displaying client/partner logos in responsive grid
- **Features:** 3-6 columns, grayscale mode, hover effects, optional headings
- **Files created:**
  - `blocks/e365-logo-grid/block.json`
  - `blocks/e365-logo-grid/template.php`
  - `acf-json/group_e365_logo_grid.json`

#### [Code] E365 Testimonial v2 block
- **Created:** Improved testimonial block replacing buggy legacy version
- **Improvements over legacy:**
  - Proper centering with Tailwind flexbox
  - Responsive text sizes (sm → lg breakpoints)
  - Layout options (centered/left-aligned)
  - Background style options (light/white/dark/brand/none)
  - Conditional rendering for all elements
  - Decorative quote marks with CSS pseudo-elements
  - Separate CTA link field (not embedded in WYSIWYG)
- **Files created:**
  - `blocks/e365-testimonial/block.json`
  - `blocks/e365-testimonial/template.php`
  - `acf-json/group_e365_testimonial.json`
  - `src/input.css` - Added testimonial CSS

#### [Code] E365 Grid Block - Complete ratio and multi-column support
- **Added:** CSS rules for 70-30 and 30-70 ratio variations
- **Fixed:** 3-column and 4-column layouts - replaced `calc()` with `flex-1` to handle gaps correctly
- **Mobile:** Verified stacking works via `flex-col lg:flex-row` pattern
- **Files modified:**
  - `src/input.css` - Added missing ratio rules, fixed multi-column layouts
  - `style.tailwind.css` - Rebuilt

### 2026-01-15

#### [Code] E365 Grid Block - Fixed horizontal column layout
- **Problem:** Columns were stacking vertically despite correct flex settings
- **Root cause:** `width: 50%` + `gap: 32px` exceeded 100% container width
- **Solution:** Added CSS rules using parent `data-columns` and `data-ratio` attributes to apply `flex-1` for 2-column layouts, which handles gaps correctly
- **Files modified:**
  - `src/input.css` - Added column width rules based on parent data attributes
  - `style.tailwind.css` - Rebuilt with new CSS rules
- **Tested:** Columns now display side-by-side on frontend (verified with screenshot and JS inspection)

### 2026-01-14

#### [Code] Initial Setup - Project documentation and workflow
- Created comprehensive CLAUDE.md with project overview
- Renamed `claude/` to `_claude/` per convention
- Updated session-log.md with project context
- Set up .claude/settings.local.json with hooks
- **Files created/modified:**
  - `CLAUDE.md`
  - `_claude/session-log.md`
  - `.claude/settings.local.json`
- **Next:** Ready for feature development

---

## Next Steps (Prioritized)

1. [ ] **Logo Grid hover fix** - Feilsøk hvorfor hover-effekter ikke fungerer
   - Sjekk kompilert CSS for hover-klasser
   - Kjør `npm run build`
   - Test i DevTools
2. [ ] Debug E365 Section background color issue (reported earlier)
3. [ ] Review uncommitted changes and consider committing
4. [ ] Test alle nye blokker grundig i browser

---

## Relevant Files

| File | Description |
|------|-------------|
| `CLAUDE.md` | Project documentation for Claude |
| `_claude/instructions.md` | Workflow instructions for Claude Code |
| `_claude/feature-request-guide.md` | Feature request guide |
| `functions.php` | Main theme setup file |
| `blocks/e365-grid/` | E365 Grid block (parent for columns) |
| `blocks/e365-column/` | E365 Column block (child of grid) |
| `src/input.css` | Tailwind input with E365 block styles |
| `inc/responsive-helpers.php` | Helper functions for responsive classes |
| `blocks/e365-logo-grid/` | E365 Logo Grid block |
| `blocks/e365-testimonial/` | E365 Testimonial v2 block |
| `tailwind.config.editor.js` | Scoped Tailwind config for editor |
| `src/editor.css` | Editor-only Tailwind input |
| `style.editor.css` | Compiled scoped CSS for editor |

---

## Notes

- Local development using MAMP
- Theme uses conventional commits format
- Skills available: @design, @backend, @frontend, @review
