<?php
/**
 * E365 Block: Grid
 *
 * Universal column layout with 1-4 columns.
 * Uses E365 Column child blocks for content organization.
 * Wrap in e365-section for backgrounds/spacing.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-grid';
$is_preview = e365_is_block_editor();

// Get grid settings from ACF fields
$columns = get_field('columns') ?: 2;
$columns = intval($columns);
$gap = get_field('gap_size') ?: 'md';
$valign = get_field('vertical_align') ?: 'top';
$ratio = get_field('column_ratio') ?: '50-50';
$stack = get_field('stack_on_mobile') !== false; // Default true
$breakpoint = get_field('stack_breakpoint') ?: 'lg';
$reverse = get_field('reverse_on_mobile') ?: false;

// Block ID
$block_id = !empty($block['anchor']) ? $block['anchor'] : $block_class . '-' . $block['id'];

// Build initial template with columns
$column_template = [];
for ($i = 1; $i <= $columns; $i++) {
    $width_class = e365_column_width_class($i, $columns, $ratio);
    $order_class = e365_order_classes($reverse, $i, $columns);

    $column_template[] = [
        'acf/e365-column',
        [
            'data' => [
                'column_width_class' => trim($width_class . ' ' . $order_class),
            ],
        ],
    ];
}

// Only allow e365-column blocks inside the grid
$allowed_blocks = ['acf/e365-column'];
?>

<div id="<?php echo esc_attr($block_id); ?>"
     class="<?php echo esc_attr($block_class); ?>"
     data-columns="<?php echo esc_attr($columns); ?>"
     data-gap="<?php echo esc_attr($gap); ?>"
     data-valign="<?php echo esc_attr($valign); ?>"
     data-stack="<?php echo esc_attr($stack ? 'true' : 'false'); ?>"
     data-breakpoint="<?php echo esc_attr($breakpoint); ?>"
     data-ratio="<?php echo esc_attr($ratio); ?>"
     data-reverse="<?php echo esc_attr($reverse ? 'true' : 'false'); ?>">
<InnerBlocks />
</div>
