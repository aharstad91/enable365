# E365 Block System

> Documentation for the Enable365 ACF block system.

---

## Architecture: Atom + Layout

The E365 block system follows an **Atom + Layout** architecture:

- **Layout blocks**: Containers that handle styling (backgrounds, spacing, colors) and structure (columns, grids)
- **Atom blocks**: Small, reusable components with no layout responsibility - they fill their container

### Block Categories

| Category | Slug | Purpose |
|----------|------|---------|
| **E365 Layout** | `e365-layout` | Layout blocks (sections, grids) |
| **E365 Komponenter** | `e365-components` | Atom blocks (video, buttons, etc.) |

---

## Available Blocks

### Layout Blocks

#### `acf/e365-section`
Wrapper block with background, spacing, and color settings.

**Use for:**
- Adding backgrounds (color, gradient, image)
- Controlling vertical spacing
- Setting text colors
- Container width control

**ACF Fields:**
- `background_type`: none | color | gradient | image
- `background_color`: Color picker
- `background_gradient`: Group with start, end, direction
- `background_image`: Image field
- `padding_top/bottom`: none | sm | md | lg | xl | 2xl
- `margin_top/bottom`: none | sm | md | lg | xl | 2xl
- `text_color`: default | light | dark | muted
- `accent_color`: Color picker (for buttons/links)
- `container_width`: full | wide | standard | narrow | content
- `content_alignment`: left | center | right

#### `acf/e365-grid`
Universal column layout with 1-4 columns. Uses child `e365-column` blocks.

**Use for:**
- Multi-column layouts
- Custom column ratios (2-column only)
- Responsive stacking behavior

**ACF Fields:**
- `columns`: 1 | 2 | 3 | 4
- `column_ratio`: 50-50 | 60-40 | 40-60 | 70-30 | 30-70 | 66-33 | 33-66
- `gap_size`: none | sm | md | lg | xl
- `vertical_align`: top | center | bottom | stretch
- `stack_on_mobile`: true/false
- `stack_breakpoint`: sm | md | lg
- `reverse_on_mobile`: true/false (2-column only)

**Editor Behavior:**
- Auto-syncs number of column blocks with `columns` setting
- Each column is a separate `e365-column` block with its own InnerBlocks
- Changing column count adds/removes column blocks automatically

#### `acf/e365-column`
Single column within E365 Grid. Child block only.

**Use for:**
- Container for content within a grid column
- Cannot be used outside of `e365-grid`

**ACF Fields:**
- `column_width_class`: Auto-set by parent grid (read-only)

### Atom Blocks

#### `acf/e365-video`
Video with thumbnail and play button. Opens in modal/lightbox.

**ACF Fields:**
- `image`: Thumbnail image (required)
- `video_url`: YouTube/Vimeo URL (required)
- `aspect_ratio`: 16/9 | 4/3 | 1/1 | 9/16 | 3/2 | 2/3

---

## Helper Functions

### Block Helpers (`inc/block-helpers.php`)

```php
// Open block wrapper with all base settings applied
e365_block_wrapper_open($block, 'my-block-class');

// Close block wrapper
e365_block_wrapper_close();

// Get spacing classes from ACF fields
$classes = e365_get_spacing_classes();

// Get background inline styles
$style = e365_get_background_style($bg_type);

// Get text color class
$class = e365_get_text_color_class('light'); // Returns 'text-white'

// Get container width class
$class = e365_get_container_class('standard'); // Returns 'max-w-[1280px] mx-auto px-4 lg:px-8'

// Check if in block editor
if (e365_is_block_editor()) { ... }

// Get placeholder HTML
echo e365_block_placeholder('Block Name', 'Custom message');
```

### Responsive Helpers (`inc/responsive-helpers.php`)

```php
// Generate responsive grid classes
$classes = e365_grid_classes(
    $columns = 2,
    $gap = 'md',
    $valign = 'top',
    $stack = true,
    $breakpoint = 'lg'
);
// Returns: 'grid gap-6 lg:gap-8 items-start grid-cols-1 lg:grid-cols-2'

// Get column width class for ratios
$class = e365_column_width_class($column_index, $total_cols, $ratio);
// Returns: 'lg:w-3/5' for first column in 60-40 ratio

// Generate responsive flex classes
$classes = e365_responsive_flex([
    'direction' => 'col',
    'direction_lg' => 'row',
    'gap' => 6,
    'gap_lg' => 12,
    'align' => 'center',
]);

// Get responsive text size classes
$classes = e365_responsive_text('xl');
// Returns: 'text-xl lg:text-2xl'

// Get button classes
$classes = e365_button_classes('primary', 'md');

// Get aspect ratio class
$class = e365_aspect_ratio('16/9');
// Returns: 'aspect-video'

// Get order classes for mobile reordering
$class = e365_order_classes($reverse = true, $index = 1, $total = 2);
// Returns: 'order-2 lg:order-1'
```

---

## Creating New Blocks

### 1. Create block directory

```
blocks/e365-[name]/
├── block.json
└── template.php
```

### 2. block.json template

**For Layout blocks:**
```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "acf/e365-[name]",
  "title": "E365 [Title]",
  "description": "[Description]",
  "category": "e365-layout",
  "icon": "[dashicon]",
  "acf": {
    "mode": "preview",
    "renderTemplate": "template.php"
  },
  "supports": {
    "align": ["full", "wide"],
    "anchor": true,
    "jsx": true
  }
}
```

**For Atom blocks:**
```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "acf/e365-[name]",
  "title": "E365 [Title]",
  "description": "[Description]",
  "category": "e365-components",
  "icon": "[dashicon]",
  "acf": {
    "mode": "preview",
    "renderTemplate": "template.php"
  },
  "supports": {
    "anchor": true
  }
}
```

### 3. template.php template

```php
<?php
/**
 * E365 Block: [Name]
 */
defined('ABSPATH') || exit;

$block_class = 'e365-[name]';
$is_preview = e365_is_block_editor();

// Get field values
$title = get_field('title');
?>

<?php e365_block_wrapper_open($block, $block_class); ?>

<div class="<?php echo esc_attr($block_class); ?>__inner">
    <?php if ($is_preview && empty($title)): ?>
        <?php echo e365_block_placeholder('Block Name'); ?>
    <?php else: ?>
        <!-- Block content -->
    <?php endif; ?>
</div>

<?php e365_block_wrapper_close(); ?>
```

### 4. Register in functions.php

Add to the `$e365_blocks` array:
```php
$e365_blocks = [
    // ... existing blocks
    'e365-[name]' => '/blocks/e365-[name]/block.json',
];
```

### 5. Create ACF field group

Create `acf-json/group_e365_[name].json` with fields for the block.

---

## CSS Variables

The block system sets these CSS variables:

| Variable | Set by | Usage |
|----------|--------|-------|
| `--e365-accent` | Section block | Button colors, links |

---

## Spacing Scale

| Key | Mobile | Desktop |
|-----|--------|---------|
| `none` | 0 | 0 |
| `sm` | 16px (4) | 24px (6) |
| `md` | 32px (8) | 48px (12) |
| `lg` | 48px (12) | 64px (16) |
| `xl` | 64px (16) | 80px (20) |
| `2xl` | 96px (24) | 128px (32) |

---

## Responsive Behavior

### Grid Stacking

| Columns | Mobile | Tablet (sm) | Desktop (lg) |
|---------|--------|-------------|--------------|
| 1 | 1 col | 1 col | 1 col |
| 2 | 1 col | 1 col | 2 cols |
| 3 | 1 col | 2 cols | 3 cols |
| 4 | 1 col | 2 cols | 4 cols |

### Container Widths

| Key | Max Width |
|-----|-----------|
| `full` | 100% |
| `wide` | 1440px |
| `standard` | 1280px |
| `narrow` | 960px |
| `content` | 720px |

---

## Important Notes

1. **Never modify legacy blocks** - They're in use on production
2. **Always run `npm run build:css`** after adding new Tailwind classes
3. **Use helper functions** for consistent output
4. **Atom blocks have no layout responsibility** - they fill their container
5. **Wrap in `e365-section` for backgrounds/spacing** - Grid blocks are pure layout
