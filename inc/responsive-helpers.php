<?php
/**
 * E365 Responsive Helper Functions
 *
 * Provides utility functions for generating responsive
 * Tailwind CSS classes for grid and layout components.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Generate responsive grid classes for e365-grid block
 *
 * @param int    $columns    Number of columns (1-4)
 * @param string $gap        Gap size: none, sm, md, lg, xl
 * @param string $valign     Vertical alignment: top, center, bottom, stretch
 * @param bool   $stack      Whether to stack on mobile
 * @param string $breakpoint Breakpoint for stacking: sm, md, lg
 * @return string Tailwind CSS classes
 */
function e365_grid_classes($columns = 2, $gap = 'md', $valign = 'top', $stack = true, $breakpoint = 'lg') {
    // Use flexbox for better ratio support
    $classes = ['flex', 'flex-wrap'];

    // Gap mapping
    $gap_map = [
        'none' => '0',
        'sm'   => '4',
        'md'   => '6',
        'lg'   => '8',
        'xl'   => '12',
    ];
    $gap_desktop_map = [
        'none' => '0',
        'sm'   => '6',
        'md'   => '8',
        'lg'   => '12',
        'xl'   => '16',
    ];

    // Add gap classes
    $gap_value = $gap_map[$gap] ?? '6';
    $gap_desktop = $gap_desktop_map[$gap] ?? '8';
    $classes[] = 'gap-' . $gap_value;
    $classes[] = 'lg:gap-' . $gap_desktop;

    // Vertical alignment
    $valign_map = [
        'top'     => 'items-start',
        'center'  => 'items-center',
        'bottom'  => 'items-end',
        'stretch' => 'items-stretch',
    ];
    $classes[] = $valign_map[$valign] ?? 'items-start';

    // Direction based on stacking
    if ($stack) {
        $classes[] = 'flex-col';
        $classes[] = $breakpoint . ':flex-row';
    } else {
        $classes[] = 'flex-row';
    }

    return implode(' ', $classes);
}

/**
 * Get column width class for specific column in a grid
 *
 * @param int    $column_index Current column (1-based)
 * @param int    $total_cols   Total number of columns
 * @param string $ratio        Ratio for 2-column layouts
 * @return string Tailwind CSS classes
 */
function e365_column_width_class($column_index, $total_cols, $ratio = '50-50') {
    // Base class for mobile (full width)
    $base = 'w-full';

    // For single column, just return full width
    if ($total_cols === 1) {
        return $base;
    }

    // For 3+ columns, equal distribution
    if ($total_cols >= 3) {
        $width_map = [
            3 => 'lg:w-1/3',
            4 => 'lg:w-1/4',
        ];
        return $base . ' ' . ($width_map[$total_cols] ?? 'lg:w-1/4');
    }

    // Ratio mapping for 2-column layouts (need to account for gap)
    // Using flex-1 with basis for better gap handling
    $ratio_map = [
        '50-50' => ['lg:flex-1', 'lg:flex-1'],
        '60-40' => ['lg:w-3/5', 'lg:w-2/5'],
        '40-60' => ['lg:w-2/5', 'lg:w-3/5'],
        '70-30' => ['lg:w-[calc(70%-1rem)]', 'lg:w-[calc(30%-1rem)]'],
        '30-70' => ['lg:w-[calc(30%-1rem)]', 'lg:w-[calc(70%-1rem)]'],
        '66-33' => ['lg:w-2/3', 'lg:w-1/3'],
        '33-66' => ['lg:w-1/3', 'lg:w-2/3'],
    ];

    $widths = $ratio_map[$ratio] ?? $ratio_map['50-50'];

    return $base . ' ' . ($widths[$column_index - 1] ?? 'lg:flex-1');
}

/**
 * Generate responsive flex classes
 *
 * @param array $config Configuration array
 * @return string Tailwind CSS classes
 */
function e365_responsive_flex($config = []) {
    $defaults = [
        'direction'    => 'col',
        'direction_lg' => 'row',
        'gap'          => 6,
        'gap_lg'       => 12,
        'align'        => 'start',
        'justify'      => 'start',
        'wrap'         => false,
    ];

    $config = wp_parse_args($config, $defaults);

    $classes = [
        'flex',
        'flex-' . $config['direction'],
        'lg:flex-' . $config['direction_lg'],
        'gap-' . $config['gap'],
        'lg:gap-' . $config['gap_lg'],
        'items-' . $config['align'],
        'justify-' . $config['justify'],
    ];

    if ($config['wrap']) {
        $classes[] = 'flex-wrap';
    }

    return implode(' ', $classes);
}

/**
 * Generate responsive text size classes
 *
 * @param string $size Size key: sm, base, lg, xl, 2xl, 3xl, 4xl, 5xl
 * @return string Tailwind CSS classes
 */
function e365_responsive_text($size = 'base') {
    $sizes = [
        'sm'   => 'text-sm lg:text-base',
        'base' => 'text-base lg:text-lg',
        'lg'   => 'text-lg lg:text-xl',
        'xl'   => 'text-xl lg:text-2xl',
        '2xl'  => 'text-2xl lg:text-3xl',
        '3xl'  => 'text-3xl lg:text-4xl',
        '4xl'  => 'text-4xl lg:text-5xl',
        '5xl'  => 'text-5xl lg:text-6xl',
    ];

    return $sizes[$size] ?? $sizes['base'];
}

/**
 * Generate button classes with variant and size
 *
 * @param string $variant Button variant: primary, secondary, outline, ghost
 * @param string $size    Button size: sm, md, lg
 * @return string Tailwind CSS classes
 */
function e365_button_classes($variant = 'primary', $size = 'md') {
    $base = 'inline-flex items-center justify-center font-semibold rounded-lg transition-all duration-200 no-underline';

    $variants = [
        'primary'   => 'bg-[var(--e365-accent,#AA1010)] text-white hover:opacity-90 shadow hover:shadow-md',
        'secondary' => 'bg-slate-100 text-slate-900 hover:bg-slate-200',
        'outline'   => 'border-2 border-[var(--e365-accent,#AA1010)] text-[var(--e365-accent,#AA1010)] hover:bg-[var(--e365-accent,#AA1010)] hover:text-white',
        'ghost'     => 'text-[var(--e365-accent,#AA1010)] hover:bg-slate-100',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-5 py-2.5 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];

    return $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
}

/**
 * Generate card classes
 *
 * @param array $config Configuration array
 * @return string Tailwind CSS classes
 */
function e365_card_classes($config = []) {
    $defaults = [
        'border'  => true,
        'shadow'  => 'sm',
        'rounded' => 'xl',
        'padding' => 6,
        'hover'   => true,
        'bg'      => 'white',
    ];

    $config = wp_parse_args($config, $defaults);

    $classes = ['overflow-hidden'];

    // Background
    $classes[] = 'bg-' . $config['bg'];

    // Border
    if ($config['border']) {
        $classes[] = 'border border-slate-200';
    }

    // Shadow
    if ($config['shadow']) {
        $classes[] = 'shadow-' . $config['shadow'];
    }

    // Rounded
    if ($config['rounded']) {
        $classes[] = 'rounded-' . $config['rounded'];
    }

    // Padding
    if ($config['padding']) {
        $classes[] = 'p-' . $config['padding'];
    }

    // Hover
    if ($config['hover']) {
        $classes[] = 'hover:shadow-md transition-shadow duration-200';
    }

    return implode(' ', $classes);
}

/**
 * Get aspect ratio class
 *
 * @param string $ratio Aspect ratio: 16/9, 4/3, 1/1, 9/16, 3/2
 * @return string Tailwind CSS class
 */
function e365_aspect_ratio($ratio = '16/9') {
    $ratio_map = [
        '16/9' => 'aspect-video',
        '4/3'  => 'aspect-[4/3]',
        '1/1'  => 'aspect-square',
        '9/16' => 'aspect-[9/16]',
        '3/2'  => 'aspect-[3/2]',
        '2/3'  => 'aspect-[2/3]',
    ];

    return $ratio_map[$ratio] ?? 'aspect-video';
}

/**
 * Generate order classes for mobile column reordering
 *
 * @param bool $reverse Whether to reverse on mobile
 * @param int  $index   Column index (1-based)
 * @param int  $total   Total columns
 * @return string Tailwind CSS classes
 */
function e365_order_classes($reverse, $index, $total) {
    if (!$reverse || $total < 2) {
        return '';
    }

    // For 2 columns, swap order on mobile
    if ($total === 2) {
        if ($index === 1) {
            return 'order-2 lg:order-1';
        } else {
            return 'order-1 lg:order-2';
        }
    }

    return '';
}
