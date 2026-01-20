<?php
/**
 * E365 Block: Section
 *
 * Wrapper block with background, spacing, and color settings.
 * Use this to wrap other blocks for consistent styling.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-section';
$is_preview = e365_is_block_editor();

// Debug background - remove after testing
$debug_bg_type = get_field('background_type');
$debug_bg_color = get_field('background_color');
$debug_all_fields = get_fields();
if ($is_preview) {
    echo '<!-- E365 Section Debug: bg_type=' . esc_html($debug_bg_type) . ', bg_color=' . esc_html($debug_bg_color) . ' -->';
    echo '<!-- All fields: ' . esc_html(print_r($debug_all_fields, true)) . ' -->';
    echo '<!-- Block data: ' . esc_html(print_r($block['data'] ?? 'no data', true)) . ' -->';
}

// Get allowed inner blocks - all layout and component blocks
$allowed_blocks = [
    'acf/e365-grid',
    'acf/e365-media-content',
    'acf/e365-video',
    'acf/e365-logo-grid',
    'acf/e365-testimonial',
    'core/paragraph',
    'core/heading',
    'core/image',
    'core/list',
    'core/buttons',
    'core/group',
    'core/columns',
    'core/spacer',
];

// InnerBlocks template - empty by default
$inner_template = [];
?>

<?php e365_block_wrapper_open($block, $block_class); ?>

<?php $inner_classes = e365_get_inner_classes(); ?>
<div class="<?php echo esc_attr($block_class); ?>__inner <?php echo esc_attr($inner_classes); ?>">
    <?php if ($is_preview && empty($block['data'])): ?>
        <div class="e365-block-placeholder bg-slate-100 border-2 border-dashed border-slate-300 rounded-lg p-8 text-center text-slate-500">
            <p class="m-0 text-lg font-medium">E365 Seksjon</p>
            <p class="m-0 mt-2 text-sm">Legg til innhold her, og konfigurer bakgrunn, spacing og farger i sidefeltene.</p>
        </div>
    <?php endif; ?>

    <InnerBlocks
        allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
        template="<?php echo esc_attr(wp_json_encode($inner_template)); ?>"
    />
</div>

<?php e365_block_wrapper_close(); ?>
