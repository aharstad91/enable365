<?php
/**
 * E365 Block: Media + Content
 *
 * Two-column layout with media (image/video) on one side
 * and Gutenberg blocks on the other. Supports responsive reordering.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

$block_class = 'e365-media-content';
$is_preview = e365_is_block_editor();

// Get ACF field values
$media_type = get_field('media_type') ?: 'image';
$image = get_field('media_image');
$video_url = get_field('media_video_url');
$media_position = get_field('media_position') ?: 'left';
$mobile_order = get_field('mobile_order') ?: 'media-first';
$ratio = get_field('ratio') ?: '50-50';
$gap = get_field('gap') ?: 'lg';
$vertical_align = get_field('vertical_align') ?: 'center';
$image_rounded = get_field('image_rounded') !== false; // Default true
$image_shadow = get_field('image_shadow') ?: false;

// Block ID
$block_id = !empty($block['anchor']) ? $block['anchor'] : $block_class . '-' . $block['id'];

// Gap classes
$gap_map = [
    'none' => 'gap-0',
    'sm'   => 'gap-4 lg:gap-6',
    'md'   => 'gap-6 lg:gap-8',
    'lg'   => 'gap-8 lg:gap-12',
    'xl'   => 'gap-12 lg:gap-16',
];
$gap_class = $gap_map[$gap] ?? $gap_map['lg'];

// Vertical alignment classes
$valign_map = [
    'top'     => 'items-start',
    'center'  => 'items-center',
    'bottom'  => 'items-end',
    'stretch' => 'items-stretch',
];
$valign_class = $valign_map[$vertical_align] ?? 'items-center';

// Ratio classes for columns
$ratio_map = [
    '50-50' => ['media' => 'lg:w-1/2', 'content' => 'lg:w-1/2'],
    '60-40' => ['media' => 'lg:w-3/5', 'content' => 'lg:w-2/5'],
    '40-60' => ['media' => 'lg:w-2/5', 'content' => 'lg:w-3/5'],
    '55-45' => ['media' => 'lg:w-[55%]', 'content' => 'lg:w-[45%]'],
    '45-55' => ['media' => 'lg:w-[45%]', 'content' => 'lg:w-[55%]'],
];
$ratios = $ratio_map[$ratio] ?? $ratio_map['50-50'];

// Order classes based on position and mobile order
$media_order = '';
$content_order = '';

if ($media_position === 'right') {
    // Media on right: content first on desktop
    $media_order = 'lg:order-2';
    $content_order = 'lg:order-1';
}

// Mobile order override
if ($mobile_order === 'content-first') {
    $media_order .= ' order-2';
    $content_order .= ' order-1';
} else {
    $media_order .= ' order-1';
    $content_order .= ' order-2';
}

// Image classes
$image_classes = ['w-full', 'h-auto', 'block'];
if ($image_rounded) {
    $image_classes[] = 'rounded-xl';
}
if ($image_shadow) {
    $image_classes[] = 'shadow-lg';
}

// Check if we have media
$has_media = ($media_type === 'image' && !empty($image)) || ($media_type === 'video' && !empty($video_url));

// Check if image is animated (GIF)
$is_animated = false;
if ($media_type === 'image' && is_array($image) && !empty($image['mime_type'])) {
    $is_animated = $image['mime_type'] === 'image/gif';
}
?>

<div id="<?php echo esc_attr($block_id); ?>"
     class="<?php echo esc_attr($block_class); ?>"
     data-media-position="<?php echo esc_attr($media_position); ?>"
     data-ratio="<?php echo esc_attr($ratio); ?>">

    <div class="<?php echo esc_attr($block_class); ?>__inner flex flex-col lg:flex-row <?php echo esc_attr($gap_class); ?> <?php echo esc_attr($valign_class); ?>">

        <!-- Media Column -->
        <div class="<?php echo esc_attr($block_class); ?>__media w-full <?php echo esc_attr($ratios['media']); ?> <?php echo esc_attr(trim($media_order)); ?>">
            <?php if ($is_preview && !$has_media): ?>
                <div class="e365-block-placeholder bg-slate-100 border-2 border-dashed border-slate-300 rounded-xl aspect-video flex items-center justify-center text-slate-500">
                    <span>Velg bilde eller video i sidefeltene</span>
                </div>
            <?php elseif ($media_type === 'image' && !empty($image)): ?>
                <?php
                $img_src = is_array($image) ? $image['url'] : $image;
                $img_alt = is_array($image) ? ($image['alt'] ?? '') : '';
                // Don't use srcset for GIFs - it breaks animation
                $img_srcset = (!$is_animated && is_array($image) && isset($image['ID'])) ? wp_get_attachment_image_srcset($image['ID']) : '';
                ?>
                <img
                    src="<?php echo esc_url($img_src); ?>"
                    alt="<?php echo esc_attr($img_alt); ?>"
                    <?php if ($img_srcset): ?>srcset="<?php echo esc_attr($img_srcset); ?>"<?php endif; ?>
                    <?php if (!$is_animated): ?>sizes="(max-width: 1024px) 100vw, 50vw"<?php endif; ?>
                    class="<?php echo esc_attr(implode(' ', $image_classes)); ?>"
                    <?php if (!$is_animated): ?>loading="lazy"<?php endif; ?>
                />
            <?php elseif ($media_type === 'video' && $video_url): ?>
                <?php
                // Extract video ID for thumbnail
                $video_id = '';
                $thumbnail_url = '';

                if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches);
                    $video_id = $matches[1] ?? '';
                    if ($video_id) {
                        $thumbnail_url = 'https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
                    }
                }
                ?>
                <div class="<?php echo esc_attr($block_class); ?>__video-wrapper relative <?php echo $image_rounded ? 'rounded-xl overflow-hidden' : ''; ?> <?php echo $image_shadow ? 'shadow-lg' : ''; ?> cursor-pointer group"
                     data-video-url="<?php echo esc_url($video_url); ?>"
                     onclick="e365OpenVideoModal(this)">
                    <?php if ($thumbnail_url): ?>
                        <img src="<?php echo esc_url($thumbnail_url); ?>"
                             alt="Video thumbnail"
                             class="w-full h-auto block aspect-video object-cover"
                             loading="lazy" />
                    <?php else: ?>
                        <div class="aspect-video bg-slate-200 flex items-center justify-center">
                            <span class="text-slate-500">Video</span>
                        </div>
                    <?php endif; ?>
                    <!-- Play button overlay -->
                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/30 transition-colors">
                        <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/90 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 text-[#AA1010] ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Content Column -->
        <div class="<?php echo esc_attr($block_class); ?>__content w-full <?php echo esc_attr($ratios['content']); ?> <?php echo esc_attr(trim($content_order)); ?>">
            <?php if ($is_preview): ?>
                <InnerBlocks />
            <?php else: ?>
                <InnerBlocks />
            <?php endif; ?>
        </div>

    </div>
</div>
