<?php
/**
 * E365 Block: Column
 *
 * A single column within E365 Grid.
 * Pure content container - inherits layout from parent grid.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-column';
$is_preview = e365_is_block_editor();

// Get column settings (set by parent grid or overridden)
$width_class = get_field('column_width_class') ?: '';

// Block ID
$block_id = !empty($block['anchor']) ? $block['anchor'] : $block_class . '-' . $block['id'];

// Allowed inner blocks
$allowed_blocks = [
    'acf/e365-video',
    'acf/e365-grid', // Allow nested grids
    'core/paragraph',
    'core/heading',
    'core/image',
    'core/list',
    'core/buttons',
    'core/group',
    'core/cover',
    'core/spacer',
    'core/separator',
    'acf/bulletlist-checkmark',
    'acf/testimonial-block',
    'acf/staff-card',
];
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($block_class); ?> <?php echo esc_attr($width_class); ?>">
    <InnerBlocks />
</div>
