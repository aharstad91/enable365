<?php
/**
 * E365 Block Helper Functions
 *
 * Provides utility functions for consistent block rendering
 * across all E365 ACF blocks.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Check if we're in the block editor
 *
 * @return bool
 */
function e365_is_block_editor() {
    return is_admin() || (defined('REST_REQUEST') && REST_REQUEST);
}

/**
 * Get block wrapper attributes with base settings applied
 *
 * @param array  $block      The ACF block array
 * @param string $base_class The base CSS class for the block
 * @return array Array with 'id', 'class', and 'style' keys
 */
function e365_get_block_wrapper_attrs($block, $base_class = '') {
    // Block ID
    $id = !empty($block['anchor']) ? $block['anchor'] : $base_class . '-' . $block['id'];

    // Start building classes
    $classes = ['e365-block', 'ani-clean'];

    if ($base_class) {
        $classes[] = $base_class;
    }

    if (!empty($block['className'])) {
        $classes[] = $block['className'];
    }

    if (!empty($block['align'])) {
        $classes[] = 'align' . $block['align'];
    }

    // Get base settings
    $bg_type = get_field('background_type');
    $text_color = get_field('text_color');
    $container_width = get_field('container_width');
    $content_align = get_field('content_alignment');

    // Add alignfull when background is set (to break out of container)
    if ($bg_type && $bg_type !== 'none') {
        $classes[] = 'alignfull';
    }

    // Add spacing classes
    $classes[] = e365_get_spacing_classes();

    // Add text color class
    if ($text_color && $text_color !== 'default') {
        $classes[] = e365_get_text_color_class($text_color);
    }

    // Note: Container width and content alignment are now handled by inner element
    // See e365_get_inner_classes() function

    // Build inline styles for background
    $style = e365_get_background_style($bg_type);

    // Add accent color CSS variable
    $accent = get_field('accent_color');
    if ($accent) {
        $style .= ($style ? '; ' : '') . '--e365-accent: ' . esc_attr($accent) . ';';
    }

    return [
        'id'    => esc_attr($id),
        'class' => esc_attr(implode(' ', array_filter($classes))),
        'style' => esc_attr($style),
    ];
}

/**
 * Get inner container classes for section blocks
 * These classes go on the inner element to constrain content width
 * while allowing wrapper to be full-width for backgrounds
 *
 * @return string Space-separated CSS classes
 */
function e365_get_inner_classes() {
    $container_width = get_field('container_width');
    $content_align = get_field('content_alignment');

    $classes = [];

    // Add container width class
    if ($container_width) {
        $classes[] = e365_get_container_class($container_width);
    }

    // Add alignment class
    if ($content_align && $content_align !== 'left') {
        $classes[] = 'text-' . $content_align;
    }

    return implode(' ', array_filter($classes));
}

/**
 * Get spacing Tailwind classes from ACF fields
 *
 * @return string Space-separated CSS classes
 */
function e365_get_spacing_classes() {
    $spacing_map = [
        'none' => '0',
        'sm'   => '4',
        'md'   => '8',
        'lg'   => '12',
        'xl'   => '16',
        '2xl'  => '24',
    ];

    // Responsive multipliers for desktop
    $desktop_map = [
        'none' => '0',
        'sm'   => '6',
        'md'   => '12',
        'lg'   => '16',
        'xl'   => '20',
        '2xl'  => '32',
    ];

    $classes = [];

    // Padding top
    $pt = get_field('padding_top') ?: 'md';
    if ($pt !== 'none') {
        $classes[] = 'pt-' . ($spacing_map[$pt] ?? '8');
        $classes[] = 'lg:pt-' . ($desktop_map[$pt] ?? '12');
    }

    // Padding bottom
    $pb = get_field('padding_bottom') ?: 'md';
    if ($pb !== 'none') {
        $classes[] = 'pb-' . ($spacing_map[$pb] ?? '8');
        $classes[] = 'lg:pb-' . ($desktop_map[$pb] ?? '12');
    }

    // Margin top
    $mt = get_field('margin_top') ?: 'none';
    if ($mt !== 'none') {
        $classes[] = 'mt-' . ($spacing_map[$mt] ?? '0');
        $classes[] = 'lg:mt-' . ($desktop_map[$mt] ?? '0');
    }

    // Margin bottom
    $mb = get_field('margin_bottom') ?: 'none';
    if ($mb !== 'none') {
        $classes[] = 'mb-' . ($spacing_map[$mb] ?? '0');
        $classes[] = 'lg:mb-' . ($desktop_map[$mb] ?? '0');
    }

    return implode(' ', $classes);
}

/**
 * Get text color class from setting value
 *
 * @param string $color The color setting value
 * @return string Tailwind CSS class
 */
function e365_get_text_color_class($color) {
    $color_map = [
        'default' => '',
        'light'   => 'text-white',
        'dark'    => 'text-slate-900',
        'muted'   => 'text-slate-600',
    ];

    return $color_map[$color] ?? '';
}

/**
 * Get container width class from setting value
 *
 * @param string $width The width setting value
 * @return string Tailwind CSS classes
 */
function e365_get_container_class($width) {
    $width_map = [
        'full'     => 'w-full',
        'wide'     => 'max-w-[1440px] mx-auto px-4 lg:px-8',
        'standard' => 'max-w-[1280px] mx-auto px-4 lg:px-8',
        'narrow'   => 'max-w-[960px] mx-auto px-4 lg:px-8',
        'content'  => 'max-w-[720px] mx-auto px-4 lg:px-8',
    ];

    return $width_map[$width] ?? $width_map['standard'];
}

/**
 * Get background inline style from ACF fields
 *
 * @param string $bg_type The background type setting
 * @return string Inline CSS style string
 */
function e365_get_background_style($bg_type) {
    if (!$bg_type || $bg_type === 'none') {
        return '';
    }

    $styles = [];

    switch ($bg_type) {
        case 'color':
            $color = get_field('background_color');
            if ($color) {
                $styles[] = 'background-color: ' . esc_attr($color);
            }
            break;

        case 'gradient':
            $gradient = get_field('background_gradient');
            if ($gradient) {
                $start = $gradient['gradient_start'] ?? '#ffffff';
                $end = $gradient['gradient_end'] ?? '#f9fafb';
                $direction = e365_gradient_direction($gradient['gradient_direction'] ?? 'to-b');
                $styles[] = 'background: linear-gradient(' . $direction . ', ' . esc_attr($start) . ', ' . esc_attr($end) . ')';
            }
            break;

        case 'image':
            $image = get_field('background_image');
            if ($image && isset($image['url'])) {
                $styles[] = "background-image: url('" . esc_url($image['url']) . "')";
                $styles[] = 'background-size: cover';
                $styles[] = 'background-position: center';
            }
            break;
    }

    return implode('; ', $styles);
}

/**
 * Convert Tailwind gradient direction to CSS
 *
 * @param string $direction Tailwind direction class
 * @return string CSS gradient direction
 */
function e365_gradient_direction($direction) {
    $map = [
        'to-t'  => 'to top',
        'to-b'  => 'to bottom',
        'to-l'  => 'to left',
        'to-r'  => 'to right',
        'to-tr' => 'to top right',
        'to-tl' => 'to top left',
        'to-br' => 'to bottom right',
        'to-bl' => 'to bottom left',
    ];

    return $map[$direction] ?? 'to bottom';
}

/**
 * Render block wrapper opening tag
 *
 * @param array  $block      The ACF block array
 * @param string $base_class The base CSS class for the block
 */
function e365_block_wrapper_open($block, $base_class = '') {
    $attrs = e365_get_block_wrapper_attrs($block, $base_class);

    printf(
        '<div id="%s" class="%s"%s>',
        $attrs['id'],
        $attrs['class'],
        $attrs['style'] ? ' style="' . $attrs['style'] . '"' : ''
    );
}

/**
 * Render block wrapper closing tag
 */
function e365_block_wrapper_close() {
    echo '</div>';
}

/**
 * Get block preview placeholder HTML
 *
 * @param string $block_name Human-readable block name
 * @param string $message    Optional custom message
 * @return string HTML for placeholder
 */
function e365_block_placeholder($block_name, $message = '') {
    $default_message = sprintf(
        __('Legg til innhold for %s via sidefeltene.', 'enable365'),
        $block_name
    );

    return sprintf(
        '<div class="e365-block-placeholder bg-slate-100 border-2 border-dashed border-slate-300 rounded-lg p-8 text-center text-slate-500">
            <p class="m-0">%s</p>
        </div>',
        esc_html($message ?: $default_message)
    );
}
