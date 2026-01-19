<?php
/**
 * E365 Block: Video
 *
 * Atom block for video with thumbnail and play button.
 * Opens video in modal/lightbox on click.
 * No layout responsibility - fills its container.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-video';
$is_preview = e365_is_block_editor();

// Get field values
$image = get_field('image');
$video_url = get_field('video_url');
$aspect_ratio = get_field('aspect_ratio') ?: '16/9';

// Get aspect ratio class
$aspect_class = e365_aspect_ratio($aspect_ratio);

// Block ID
$block_id = !empty($block['anchor']) ? $block['anchor'] : $block_class . '-' . $block['id'];

// Check if we have content
$has_content = $image && $video_url;
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($block_class); ?> w-full">
    <?php if ($is_preview && !$has_content): ?>
        <?php echo e365_block_placeholder('E365 Video', 'Legg til bilde og video-URL i sidefeltene.'); ?>
    <?php elseif ($has_content): ?>
        <div class="<?php echo esc_attr($block_class); ?>__wrapper video-card relative <?php echo esc_attr($aspect_class); ?> rounded-xl overflow-hidden cursor-pointer group"
             data-video-url="<?php echo esc_attr($video_url); ?>">

            <!-- Thumbnail Image -->
            <img src="<?php echo esc_url($image['url']); ?>"
                 alt="<?php echo esc_attr($image['alt'] ?: 'Video thumbnail'); ?>"
                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                 loading="lazy" />

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/20 transition-opacity duration-300 group-hover:bg-black/30"></div>

            <!-- Play Button -->
            <button class="<?php echo esc_attr($block_class); ?>__play absolute inset-0 flex items-center justify-center" aria-label="Spill av video">
                <span class="w-16 h-16 lg:w-20 lg:h-20 bg-[var(--e365-accent,#AA1010)] rounded-full flex items-center justify-center shadow-lg transition-transform duration-300 group-hover:scale-110">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </span>
            </button>
        </div>
    <?php else: ?>
        <!-- Frontend: Show placeholder if no content -->
        <div class="<?php echo esc_attr($aspect_class); ?> bg-slate-100 rounded-xl flex items-center justify-center">
            <span class="text-slate-400">Video ikke konfigurert</span>
        </div>
    <?php endif; ?>
</div>
