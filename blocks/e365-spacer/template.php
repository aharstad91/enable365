<?php
/**
 * E365 Block: Spacer
 *
 * Responsive spacer with separate desktop and mobile heights.
 * Values are pixel-based, stored directly as the field value.
 *
 * Desktop: 24, 48, 64, 96, 128
 * Mobile:  16, 24, 32, 48, 64
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-spacer';
$is_preview = e365_is_block_editor();

$desktop_px = intval(get_field('desktop_height') ?: 48);
$mobile_px  = intval(get_field('mobile_height') ?: 24);

// Block ID
$block_id = !empty($block['anchor']) ? $block['anchor'] : $block_class . '-' . $block['id'];
?>

<?php if ($is_preview): ?>
    <div id="<?php echo esc_attr($block_id); ?>"
         class="<?php echo esc_attr($block_class); ?> relative bg-slate-50 border border-dashed border-slate-300 flex items-center justify-center text-xs text-slate-400"
         style="height: <?php echo esc_attr($desktop_px); ?>px;">
        <span>↕ Desktop: <?php echo esc_html($desktop_px); ?>px · Mobil: <?php echo esc_html($mobile_px); ?>px</span>
    </div>
<?php else: ?>
    <div id="<?php echo esc_attr($block_id); ?>"
         class="<?php echo esc_attr($block_class); ?>"
         style="--spacer-mobile: <?php echo esc_attr($mobile_px); ?>px; --spacer-desktop: <?php echo esc_attr($desktop_px); ?>px;"
         aria-hidden="true"></div>
<?php endif; ?>
