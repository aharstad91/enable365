<?php
/**
 * Block Template: Video Image Overlay
 */
$id = 'video-overlay-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}
$className = 'video-overlay';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}
$image = get_field('image');
$video_embed = get_field('video_embed');
$video_container_id = 'video-container-' . uniqid();

// Convert YouTube URL to nocookie domain
$video_embed = str_replace('youtube.com', 'youtube-nocookie.com', $video_embed);
?>
<div id="<?php echo esc_attr($id); ?>" class="ani-clean <?php echo esc_attr($className); ?>">
	<div class="hero-container relative">
		<div class="hero-grid">
			<div class="hero-content">
				<InnerBlocks />
			</div>
			<?php if ($image && $video_embed): ?>
			<div class="hero-video">
				<div class="video-card" data-video-url="<?php echo esc_url($video_embed); ?>">
					<div class="video-thumbnail">
						<?php echo wp_get_attachment_image($image['ID'], 'full', false, array(
							'alt' => $image['alt'] ?: 'Video thumbnail'
						)); ?>
					    <div class="play-button ani-clean">
							<svg viewBox="0 0 24 24" width="48" height="48" fill="white" class="ani-clean">
								<path d="M8 5v14l11-7z" class="ani-clean"/>
							</svg>
						</div>
					</div>
					<?php if ($image['caption']): ?>
					<div class="video-title">
						<p><?php echo esc_html($image['caption']); ?></p>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
	// Create video overlay once
	const overlay = document.createElement('div');
	overlay.className = 'c-video-overlay';
	overlay.innerHTML = `
		<div class="c-video-player">
			<iframe 
				class="c-video-player__media"
				id="c-yt-player__media"
				frameborder="0"
				allowfullscreen=""
				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
				referrerpolicy="strict-origin-when-cross-origin"
				title="Video Player"
			></iframe>
			<div class="c-video-player-close-wrapper">
				<button class="c-video-player__close" aria-label="Close video">Close Video</button>
			</div>
		</div>
	`;
	document.body.appendChild(overlay);

	const iframe = overlay.querySelector('iframe');
	const closeButton = overlay.querySelector('.c-video-player__close');

	// Add click handlers for video cards
	document.querySelectorAll('.video-card, .play-button').forEach(trigger => {
		trigger.addEventListener('click', function() {
			let videoUrl = this.dataset.videoUrl;
			
			const params = new URLSearchParams({
				showinfo: '0',
				cc_load_policy: '1',
				cc_lang_pref: 'en',
				enablejsapi: '1',
				origin: window.location.origin,
				autoplay: '1',
				playsinline: '1'
			});
			
			videoUrl = videoUrl.includes('?') 
				? `${videoUrl}&${params.toString()}`
				: `${videoUrl}?${params.toString()}`;

			iframe.src = videoUrl;
			overlay.classList.add('active');
			document.body.style.overflow = 'hidden';
		});
	});

	function closeVideo() {
		overlay.classList.remove('active');
		iframe.src = '';
		document.body.style.overflow = '';
	}

	// Use the close button click as the single source of truth
	function handleClose() {
		closeButton.click();
	}

	// Event Listeners
	closeButton.addEventListener('click', closeVideo);
	
	overlay.addEventListener('click', (e) => {
		if (e.target === overlay) handleClose();
	});
	
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && overlay.classList.contains('active')) {
			handleClose();
		}
	});
});
</script>