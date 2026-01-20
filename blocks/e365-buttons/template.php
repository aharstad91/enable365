<?php
/**
 * E365 Block: Buttons
 *
 * Display up to 2 customizable buttons with responsive sizing and styling.
 * Provides better control over mobile/desktop appearance than native WP buttons.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-buttons';
$is_preview = e365_is_block_editor();

// Get buttons (repeater with max 2)
$buttons = get_field('buttons') ?: [];

// Layout options
$alignment = get_field('alignment') ?: 'left';
$gap = get_field('gap') ?: 'md';
$stack_on_mobile = get_field('stack_on_mobile') !== false; // Default true

// Block ID
$block_id = !empty($block['anchor']) ? $block['anchor'] : $block_class . '-' . $block['id'];

// Check if we have any buttons with valid links
$valid_buttons = [];
if (!empty($buttons) && is_array($buttons)) {
    foreach ($buttons as $button) {
        if (!empty($button['link'])) {
            $valid_buttons[] = $button;
        }
    }
}
$has_buttons = !empty($valid_buttons);

// Build wrapper classes
$wrapper_classes = [
    $block_class,
    'e365-block',
];

// Alignment classes
$flex_align = [
    'left' => 'justify-start',
    'center' => 'justify-center',
    'right' => 'justify-end',
];

// Gap mapping
$gap_classes = [
    'sm' => 'gap-2',
    'md' => 'gap-3 lg:gap-4',
    'lg' => 'gap-4 lg:gap-6',
];

// Build button container classes
$container_classes = [
    'e365-buttons__container',
    'flex',
    'flex-wrap',
    $flex_align[$alignment] ?? 'justify-start',
    $gap_classes[$gap] ?? 'gap-3 lg:gap-4',
];

// Stack on mobile
if ($stack_on_mobile) {
    $container_classes[] = 'flex-col sm:flex-row';
    $container_classes[] = 'items-stretch sm:items-center';
} else {
    $container_classes[] = 'flex-row';
    $container_classes[] = 'items-center';
}

/**
 * Get button classes based on style and size
 *
 * Styles:
 * - primary: Brand red filled button (default)
 * - secondary: White with red border, inverts on hover
 * - outline-light: For dark backgrounds - white border/text
 */
if (!function_exists('e365_get_button_classes')) {
function e365_get_button_classes($style, $size, $full_width_mobile) {
    $classes = [
        'e365-btn',
        'inline-flex',
        'items-center',
        'justify-center',
        'font-semibold',
        'rounded-lg',
        'transition-all',
        'duration-200',
        'no-underline',
        'cursor-pointer',
        'border-2',
        // Focus state for accessibility
        'focus:outline-none',
        'focus:ring-2',
        'focus:ring-offset-2',
    ];

    // Size classes - responsive scaling
    $size_classes = [
        'sm' => 'text-sm px-4 py-2 lg:px-5 lg:py-2.5',
        'md' => 'text-sm lg:text-base px-5 py-2.5 lg:px-6 lg:py-3',
        'lg' => 'text-base lg:text-lg px-6 py-3 lg:px-8 lg:py-4',
    ];
    $classes[] = $size_classes[$size] ?? $size_classes['md'];

    // Style classes - simplified to 3 core styles
    switch ($style) {
        case 'primary':
        default:
            $classes[] = 'e365-btn--primary';
            // Default state
            $classes[] = 'bg-[#AA1010] border-[#AA1010] text-white';
            // Hover state
            $classes[] = 'hover:bg-[#8a0d0d] hover:border-[#8a0d0d]';
            // Active/pressed state
            $classes[] = 'active:bg-[#6d0a0a] active:border-[#6d0a0a]';
            // Focus ring
            $classes[] = 'focus:ring-[#AA1010]/50';
            break;

        case 'secondary':
            $classes[] = 'e365-btn--secondary';
            // Default state
            $classes[] = 'bg-white border-[#AA1010] text-[#AA1010]';
            // Hover state - fills with brand color
            $classes[] = 'hover:bg-[#AA1010] hover:text-white';
            // Active/pressed state
            $classes[] = 'active:bg-[#8a0d0d] active:border-[#8a0d0d]';
            // Focus ring
            $classes[] = 'focus:ring-[#AA1010]/50';
            break;

        case 'outline-light':
            $classes[] = 'e365-btn--outline-light';
            // Default state - for dark backgrounds
            $classes[] = 'bg-transparent border-white text-white';
            // Hover state - inverts
            $classes[] = 'hover:bg-white hover:text-slate-900';
            // Active/pressed state
            $classes[] = 'active:bg-slate-100';
            // Focus ring - white for visibility on dark
            $classes[] = 'focus:ring-white/50';
            break;
    }

    // Full width on mobile
    if ($full_width_mobile) {
        $classes[] = 'w-full sm:w-auto';
    }

    return implode(' ', $classes);
}
} // end function_exists
?>

<div id="<?php echo esc_attr($block_id); ?>"
     class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">

    <?php if ($has_buttons): ?>
        <div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>">
            <?php foreach (array_slice($valid_buttons, 0, 2) as $index => $button):
                $text = $button['text'] ?? 'Klikk her';
                $link = $button['link'];
                $style = $button['style'] ?? 'primary';
                $size = $button['size'] ?? 'md';
                $icon = $button['icon'] ?? '';
                $icon_position = $button['icon_position'] ?? 'right';
                $new_tab = $button['new_tab'] ?? false;
                $full_width_mobile = $button['full_width_mobile'] ?? false;

                $btn_classes = e365_get_button_classes($style, $size, $full_width_mobile);
                $target = $new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';
            ?>
                <a href="<?php echo esc_url($link); ?>"
                   class="<?php echo esc_attr($btn_classes); ?>"
                   <?php echo $target; ?>>
                    <?php if ($icon && $icon_position === 'left'): ?>
                        <span class="e365-btn__icon mr-2"><?php echo $icon; ?></span>
                    <?php endif; ?>

                    <span class="e365-btn__text"><?php echo esc_html($text); ?></span>

                    <?php if ($icon && $icon_position === 'right'): ?>
                        <span class="e365-btn__icon ml-2"><?php echo $icon; ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php elseif ($is_preview): ?>
        <div class="e365-block-placeholder bg-slate-100 border-2 border-dashed border-slate-300 rounded-lg p-6 text-center text-slate-500">
            <p class="m-0 text-base font-medium">E365 Buttons</p>
            <p class="m-0 mt-2 text-sm">Legg til en lenke for å vise knappen.</p>
        </div>
    <?php endif; ?>
</div>
