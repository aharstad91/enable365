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

// Get image URL safely - handle both array and ID formats
$image_url = '';
$image_alt = '';
if (is_array($image) && !empty($image['url'])) {
    $image_url = $image['url'];
    $image_alt = $image['alt'] ?? '';
} elseif (is_numeric($image)) {
    // ACF sometimes returns just the ID
    $image_url = wp_get_attachment_url($image);
    $image_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
}

// Check if we have content
$has_content = $image_url && $video_url;
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr($block_class); ?> w-full">
    <?php if ($is_preview && !$has_content): ?>
        <?php echo e365_block_placeholder('E365 Video', 'Legg til bilde og video-URL i sidefeltene.'); ?>
    <?php elseif ($has_content): ?>
        <div class="<?php echo esc_attr($block_class); ?>__wrapper relative rounded-xl overflow-hidden cursor-pointer group"
             data-video-url="<?php echo esc_attr($video_url); ?>">

            <!-- Thumbnail Image - relative positioned to establish container height -->
            <img src="<?php echo esc_url($image_url); ?>"
                 alt="<?php echo esc_attr($image_alt ?: 'Video thumbnail'); ?>"
                 class="w-full h-auto block object-cover object-center" />

            <!-- Play Button -->
            <button class="<?php echo esc_attr($block_class); ?>__play absolute inset-0 flex items-center justify-center" aria-label="Spill av video">
                <div class="e365-video__play-icon ani-clean">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="white" class="ani-clean">
                        <path d="M8 5v14l11-7z" class="ani-clean"></path>
                    </svg>
                </div>
            </button>
        </div>

        <!-- Video Modal -->
        <div class="e365-video__modal fixed inset-0 z-[9999] hidden items-center justify-center bg-black/90 p-4"
             id="<?php echo esc_attr($block_id); ?>-modal">
            <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-10" aria-label="Lukk video">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="w-full max-w-5xl aspect-video">
                <iframe class="w-full h-full" src="" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>
    <?php else: ?>
        <!-- Frontend: Show placeholder if no content -->
        <div class="<?php echo esc_attr($aspect_class); ?> bg-slate-100 rounded-xl flex items-center justify-center">
            <span class="text-slate-400">Video ikke konfigurert</span>
        </div>
    <?php endif; ?>
</div>

<?php if ($has_content && !$is_preview): ?>
<?php
// Enqueue the video modal script once
if (!wp_script_is('e365-video-modal', 'enqueued')) {
    wp_enqueue_script('e365-video-modal', get_template_directory_uri() . '/assets/scripts/e365-video-modal.js', [], '1.0.0', true);
}
?>
<?php endif; ?>
