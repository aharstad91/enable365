# Enable365 WordPress Theme

> Main documentation file for Claude. Read this to understand the project.

---

## Project Overview

Enable365 is a custom WordPress theme for a Microsoft 365 productivity apps company. The site showcases apps like PlanIt, Agenda, Guidance, Presence, and Templates - all designed to enhance Microsoft 365 workflows. The theme includes a blog, video library, app pages, and marketing/landing pages.

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Platform** | WordPress |
| **Language** | PHP 8.x |
| **CSS Framework** | Tailwind CSS 3.4.17 |
| **JS Libraries** | Alpine.js, GSAP 3.12.5, ScrollReveal, Headroom.js |
| **Build Tools** | npm, PostCSS, Autoprefixer |
| **Fonts** | Google Fonts (Noto Sans) |
| **Fields** | Advanced Custom Fields (ACF) |
| **i18n** | WPML (multilingual) |

---

## Architecture

### Folder Structure

```
enable365/
├── acf-json/                 # ACF field group definitions
├── assets/
│   ├── scripts/              # JS libraries and utilities
│   │   ├── alpine.min.js     # Reactive components
│   │   ├── gsap/             # Animation library
│   │   ├── scrollreveal.min.js
│   │   ├── headroom.js       # Header show/hide
│   │   ├── megamenu*.js      # Navigation scripts
│   │   └── gtm.php           # GTM & LinkedIn Pixel
│   └── gfx/                  # Images and graphics
├── blocks/                   # Custom Gutenberg blocks
│   ├── application-by-category/
│   ├── applications-showcase/
│   ├── top-section-author/
│   └── video-image-overlay/
├── css/                      # CSS utilities
├── inc/                      # PHP includes
│   └── videos-cpt.php        # Videos CPT class
├── src/
│   └── input.css             # Tailwind input
├── template-parts/
│   ├── block/                # ACF block templates
│   └── gsap-animations.js    # GSAP ScrollTrigger
├── _claude/                  # Claude workflow files
├── functions.php             # Theme setup (main)
├── header.php                # Navigation & header
├── footer.php                # Footer with menus
├── style.css                 # Main stylesheet
├── style.tailwind.css        # Tailwind compiled
├── tailwind.config.js        # Tailwind config
└── theme.json                # Block editor settings
```

### Key Patterns

- **Custom Post Types**: blog, videos, apps, demo, register, newsroom, support, pricing
- **Custom Taxonomies**: blog-categories, newsroom-categories, video_app, categories, try-now
- **ACF Blocks**: bulletlist-checkmark, testimonial-block, staff-card, chooseposts_loop, applications_showcase, video-image-overlay, application-by-category, top-section-author
- **Custom Walker Classes**: For advanced menu markup with icons and descriptions
- **WPML Integration**: Language switching and translated navigation labels

---

## Important Files

| File | Purpose |
|------|---------|
| `functions.php` | Theme setup, CPTs, menus, enqueuing, blocks |
| `header.php` | Primary + mobile headers, megamenu, language selector |
| `footer.php` | 3-column menu layout, social links |
| `inc/videos-cpt.php` | Videos CPT with YouTube embed & Schema markup |
| `tailwind.config.js` | Tailwind configuration with brand colors |
| `src/input.css` | Tailwind input with heading resets |
| `template-parts/gsap-animations.js` | Front-page scroll animations |
| `blocks/application-by-category/template.php` | App tabs with Alpine.js |

---

## Custom Post Types

| CPT | Archive | Template Files |
|-----|---------|----------------|
| `blog` | /blogg/ | archive-blog.php, single-blog.php |
| `videos` | /videos/ | archive-videos.php, single-videos.php |
| `apps` | - | single-apps.php |
| `demo` | /demo/ | single-demo.php |
| `register` | /register/ | single-register.php |
| `newsroom` | - | single-newsroom.php |

---

## Development

### Setup

```bash
# Navigate to theme directory
cd /Applications/MAMP/htdocs/enable365/wp-content/themes/enable365

# Install dependencies
npm install

# Build Tailwind CSS
npm run build:css

# Watch for changes
npm run watch:css
```

### Common Commands

```bash
# Build Tailwind CSS (required before pushing new Tailwind classes)
npm run build:css

# Watch mode for development
npm run watch:css
```

### Environment

- **Local**: MAMP on macOS
- **Site URL**: http://localhost:8888/enable365/ (or similar)

---

## Conventions

### Code Style

- PHP: WordPress coding standards
- CSS: Tailwind utility classes preferred, custom CSS in style.css
- JS: Vanilla JS with Alpine.js for reactive components
- Norwegian language strings in templates

### Git

- Conventional commits: `feat:`, `fix:`, `refactor:`, `style:`, `docs:`, `chore:`
- Feature branches from main

### Tailwind Workflow

1. Add Tailwind classes to PHP templates
2. Run `npm run build:css` before committing
3. Verify classes are in style.tailwind.css
4. Commit both PHP and CSS changes

---

## ACF Configuration

### Options Page

- **Menu**: Dynamiske felter (Dynamic Fields)
- **Slug**: theme-general-settings

### Field Groups (in acf-json/)

- Navigation labels and icons
- Menu item customization
- Author/staff information
- Block-specific fields

---

## Design System

| Token | Value | Usage |
|-------|-------|-------|
| Primary | #AA1010 | Brand red, CTAs |
| Dark BG | #191715 | Footer background |
| Text Dark | #0D2538 | Headings |
| Border | #e2e8f0 | Card borders |
| Font | Noto Sans | 300, 400, 600 weights |

---

## JavaScript Architecture

### Front-page Only
- GSAP + ScrollTrigger (sticky image switching)

### Global
- Alpine.js (app category tabs)
- ScrollReveal (fade-in animations)
- Headroom.js (header hide/show)
- Megamenu scripts (desktop navigation)

---

## Related Documentation

| Document | Location |
|----------|----------|
| Session Log | `_claude/session-log.md` |
| Workflow Instructions | `_claude/instructions.md` |
| Feature Request Guide | `_claude/feature-request-guide.md` |
| Skills | `_claude/skills/` |

---

## Notes

- GSAP only loads on front-page to minimize bundle size
- ScrollReveal needs cleanup on protected elements
- Tailwind preflight is disabled to preserve WordPress styles
- SVG uploads are enabled
- WPML handles multilingual navigation via ACF option fields
